const { adminPage } = require('./admin-login.js');
const lists = ['homepage/teasers','article/index','project/index','project/landing','project/category/index','project/state/index','employee/index','team/index','team/employeeCategory/index','team/cvCategory/index','profile/index','publication/index','award/index','job/index','job/article/index','contact/index'];
const creates = ['article/create','project/create','project/category/create','project/state/create','employee/create','team/cvCategory/create','publication/create','award/create','job/article/create'];
(async () => {
  const { browser, page, errors, base } = await adminPage();
  const report = async (label) => {
    const h1 = await page.locator('h1').first().innerText().catch(() => '-');
    const stub = await page.locator('text=Noch nicht portiert').count();
    console.log(`${label.padEnd(42)} h1="${h1}" rows=${await page.locator('.listing__item').count()}${stub ? ' STUB' : ''} errors=${errors.length}`);
    errors.splice(0).forEach(e => console.log('   ' + e));
  };
  for (const l of lists) {
    await page.goto(`${base}/administration/${l}`, { waitUntil: 'networkidle' });
    await report(l);
    await page.screenshot({ path: `/tmp/tour-${l.replace(/\//g, '_')}.png` });
    const edit = page.locator('a[title=Bearbeiten]').first();
    if (await edit.count()) {
      await edit.click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(500);
      await report('  → edit ' + page.url().replace(base, ''));
      await page.screenshot({ path: `/tmp/tour-${l.replace(/\//g, '_')}-edit.png` });
      for (const tab of await page.locator('.tabs a').all()) { await tab.click(); await page.waitForTimeout(150); }
      if (errors.length) await report('  → tabs');
    }
  }
  for (const c of creates) {
    await page.goto(`${base}/administration/${c}`, { waitUntil: 'networkidle' });
    await report('create ' + c);
  }
  // CV list of a Geschäftsleitung member, landing edit, project teasers
  for (const u of ['team/cv/index/1', 'project/landing/edit/1/category', 'project/landing/edit/2/state', 'project/teaser/53']) {
    await page.goto(`${base}/administration/${u}`, { waitUntil: 'networkidle' });
    await report(u);
    await page.screenshot({ path: `/tmp/tour-${u.replace(/\//g, '_')}.png` });
  }
  await page.locator('.sidebar__logout').click();
  await page.waitForLoadState('networkidle');
  console.log('after logout:', page.url().replace(base, ''));
  await page.goto(`${base}/administration/award/index`);
  console.log('admin after logout:', page.url().replace(base, ''));
  await browser.close();
})();
