// Shared by the admin QA scripts: a logged-in page and an error log.
// Credentials from QA_EMAIL / QA_PASSWORD (a temporary local admin).
const { chromium } = require('playwright');
const base = process.env.BASE || 'https://baumbergerstegmeier.ch.test';

async function adminPage(width = 1440) {
  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width, height: 900 }, ignoreHTTPSErrors: true });
  const page = await context.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push('pageerror: ' + e.message));
  page.on('console', m => m.type() === 'error' && errors.push('console: ' + m.text()));
  page.on('response', r => r.status() >= 400 && errors.push(r.status() + ' ' + r.request().method() + ' ' + r.url()));
  await page.goto(base + '/login');
  await page.fill('input[name=email]', process.env.QA_EMAIL);
  await page.fill('input[name=password]', process.env.QA_PASSWORD);
  await Promise.all([page.waitForNavigation(), page.click('button[type=submit], input[type=submit]')]);
  return { browser, page, errors, base };
}

module.exports = { adminPage };
