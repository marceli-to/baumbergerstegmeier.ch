// Clicks through every scripted behaviour of the public site and prints
// the resulting state (classes, visibility) as text, so two builds can be
// diffed: menu, project info/credits, worklist filter, team CV and former
// employees, contact imprint/privacy, lightbox (open, next, caption,
// counter, Esc). Errors in the console are printed too.
//   NODE_TLS_REJECT_UNAUTHORIZED=0 NODE_PATH=/tmp/bs-qa/node_modules node interact.js [chromium|webkit] > out.txt
const pw = require('playwright');
const { base, pages } = require('./pages');
const engine = process.argv[2] || 'chromium';

const state = (p, selectors) => p.evaluate((sels) => sels.map((s) => {
  const els = [...document.querySelectorAll(s)];
  return `${s}: ` + els.slice(0, 6).map((el) => {
    const r = el.getBoundingClientRect();
    return `[${el.className}]${r.width && r.height ? '' : '(hidden)'}`;
  }).join(' ');
}).join('\n'), selectors);

(async () => {
  const list = await pages();
  const project = list.find((p) => p.startsWith('/projekte/') && !p.includes('uebersicht'));
  const b = await pw[engine].launch();
  for (const w of [1440, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, ignoreHTTPSErrors: true, hasTouch: w < 768 });
    const p = await ctx.newPage();
    const errs = [];
    p.on('pageerror', (e) => errs.push(e.message));
    p.on('console', (m) => m.type() === 'error' && errs.push(m.text()));
    const log = (title, text) => console.log(`--- ${w} ${title}\n${text}`);

    // Menu
    await p.goto(base + '/buero/profil', { waitUntil: 'networkidle' });
    if (await p.isVisible('[data-menu-btn]')) {
      await p.click('[data-menu-btn]');
      log('menu open', await state(p, ['[data-menu-btn]', '[data-menu]']));
    }
    const parent = p.locator('[data-menu-parent]:visible').first();
    if (await parent.count()) {
      await parent.click();
      log('menu parent', await state(p, ['[data-menu-parent]', '[data-menu-parent] + ul']));
      await parent.click();
      log('menu parent again', await state(p, ['[data-menu-parent]', '[data-menu-parent] + ul']));
    }
    if (await p.isVisible('[data-menu-btn]')) {
      await p.click('[data-menu-btn]');
      log('menu closed', await state(p, ['[data-menu-btn]', '[data-menu]']));
    }

    // Project info and credits
    await p.goto(base + project, { waitUntil: 'networkidle' });
    for (const btn of ['[data-btn-toggle-both]', '[data-btn-toggle-info]', '[data-btn-toggle-credits]']) {
      const l = p.locator(btn + ':visible').first();
      if (await l.count()) {
        await l.click();
        log(`project ${btn}`, await state(p, ['[data-btn-toggle-both]', '[data-btn-toggle-info]', '[data-btn-toggle-credits]', '[data-info]', '[data-credits]']));
      }
    }

    // Lightbox
    const thumb = p.locator('[data-fancybox]:visible, [data-lightbox]:visible').first();
    if (await thumb.count()) {
      await thumb.click();
      await p.waitForTimeout(1200);
      const dialog = async () => p.evaluate(() => {
        const d = document.querySelector('.fancybox__container, .lightbox.is-open');
        if (!d) return 'closed';
        const img = [...d.querySelectorAll('img')].find((i) => { const r = i.getBoundingClientRect(); return r.width > 50 && r.left >= 0 && r.right <= innerWidth; });
        const cap = d.querySelector('.fancybox__caption, .lightbox__caption');
        return `open; image ${img ? img.getAttribute('src').replace(/^https?:\/\/[^/]+/, '') : '-'}; caption "${cap ? cap.innerText.replace(/\s+/g, ' ').trim() : '-'}"`;
      });
      log('lightbox open', await dialog());
      await p.keyboard.press('ArrowRight');
      await p.waitForTimeout(900);
      log('lightbox next', await dialog());
      await p.keyboard.press('Escape');
      await p.waitForTimeout(900);
      log('lightbox esc', await dialog());
    }

    // Worklist filter
    await p.goto(base + '/werkliste', { waitUntil: 'networkidle' });
    const filter = p.locator('[data-btn-worklist-filter]:visible').first();
    if (await filter.count()) {
      await filter.click();
      log('worklist filter', await state(p, ['[data-worklist]', '[data-btn-worklist-filter]', '[data-worklist-items]']));
      await p.mouse.click(w - 5, 880);
      log('worklist outside', await state(p, ['[data-worklist]', '[data-btn-worklist-filter]', '[data-worklist-items]']));
    }

    // Team: CV, former employees
    await p.goto(base + '/buero/team', { waitUntil: 'networkidle' });
    const cv = p.locator('[data-btn-cv]:visible');
    if (await cv.count()) {
      await cv.nth(0).click();
      await cv.nth(1).click();
      log('team cv', await state(p, ['[data-btn-cv]', '[data-cv]']));
    }
    if (await p.isVisible('[data-btn-toggle-former]')) {
      await p.click('[data-btn-toggle-former]');
      log('team former', await state(p, ['[data-btn-toggle-former]', '[data-hidden-former]']));
    }

    // Contact: imprint, privacy
    await p.goto(base + '/kontakt', { waitUntil: 'networkidle' });
    const toggles = p.locator('[data-btn-toggle]:visible');
    for (let i = 0; i < await toggles.count(); i++) {
      await toggles.nth(i).click();
      log(`contact toggle ${i}`, await state(p, ['[data-btn-toggle]', '[data-hidden]']));
    }
    await toggles.nth(0).click();
    log('contact toggle 0 again', await state(p, ['[data-btn-toggle]', '[data-hidden]']));

    log('errors', errs.length ? errs.join('\n') : 'none');
    await ctx.close();
  }
  await b.close();
})();
