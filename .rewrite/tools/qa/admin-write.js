// End-to-end admin write test, for a LOCAL copy of production (it writes).
// Through the admin UI: creates "QA Testprojekt" (text with a link, two
// uploaded images, caption, cover, crop, hide, reorder), unpublishes and
// publishes it, adds a project teaser and a place in the Wohnen overview,
// creates "QA Publikation" with a PDF; checks the database and the public
// site after every step; deletes both at the end.
//   NODE_TLS_REJECT_UNAUTHORIZED=0 NODE_PATH=<dir with playwright>/node_modules \
//     QA_EMAIL=… QA_PASSWORD=… node admin-write.js
// Needs a temporary admin (QA_EMAIL) and the local MySQL on 127.0.0.1:3306.
// Leaves the uploaded files (…_qa-bild-*, …_qa-publikation.pdf) and the
// soft-deleted image rows; `php artisan data:import-production` restores all.
const { adminPage } = require('./admin-login.js');
const { execFileSync } = require('child_process');
const fs = require('fs');
const os = require('os');
const path = require('path');
const ROOT = path.resolve(__dirname, '../../..');
const D = fs.mkdtempSync(path.join(os.tmpdir(), 'bs-admin-write-'));
// Fixtures: two production uploads as JPEGs, a minimal PDF
fs.readdirSync(`${ROOT}/storage/app/public/uploads`).filter(f => /\.jpe?g$/i.test(f)).slice(0, 2)
  .forEach((f, i) => fs.copyFileSync(`${ROOT}/storage/app/public/uploads/${f}`, `${D}/qa-bild-${i}.jpg`));
fs.writeFileSync(`${D}/qa-publikation.pdf`, '%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj 3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n');
const sql = q => execFileSync('mysql', ['-h127.0.0.1', '-P3306', '-uroot', '--default-character-set=utf8mb4', '-N', 'baumbergerstegmeier', '-e', q]).toString().trim();
const results = [];
const ok = (label, cond, detail = '') => { results.push(`${cond ? 'OK  ' : 'FAIL'} ${label}${detail ? '  — ' + detail : ''}`); console.log(results.at(-1)); };

(async () => {
  const { browser, page, errors, base } = await adminPage();
  const shot = n => page.screenshot({ path: `${D}/${n}.png`, fullPage: true });
  const pub = async path => { const r = await page.context().request.get(base + path); return { status: r.status(), html: await r.text() }; };
  const flushErrors = label => { if (errors.length) { ok(label + ': no browser/API errors', false, errors.join(' | ')); errors.splice(0); } };
  const toastOrIdle = () => page.waitForLoadState('networkidle').then(() => page.waitForTimeout(400));
  let id, slug;

  try {
    // 1 Create project
    await page.goto(base + '/administration/project/create', { waitUntil: 'networkidle' });
    await page.locator('input[name=title]').fill('QA Testprojekt');
    await page.locator('input[name=location]').fill('Teststadt');
    await page.locator('input[name=year]').fill('2026');
    await page.locator('.ProseMirror').first().click();
    await page.keyboard.type('Dies ist ein Testtext mit einem Link.');
    for (let i = 0; i < 5; i++) await page.keyboard.press('Shift+ArrowLeft');
    await page.getByRole('button', { name: 'Link' }).first().click();
    const dialog = page.getByRole('dialog');
    await dialog.getByPlaceholder('www.example.com').fill('www.example.com');
    await dialog.getByText('In neuem Fenster öffnen').click();
    await dialog.getByRole('button', { name: 'Übernehmen' }).click();
    await page.getByRole('button', { name: 'Werkliste', exact: true }).click();
    await page.locator('input[name=type]').fill('Wohnüberbauung');
    await page.getByText('Detailseite?', { exact: true }).click();
    await page.getByText('Wohnen', { exact: true }).click();
    await page.getByText('Gebaut', { exact: true }).click();
    await page.getByRole('button', { name: 'Bilder', exact: true }).click();
    await page.locator('input[type=file]').setInputFiles([`${D}/qa-bild-0.jpg`, `${D}/qa-bild-1.jpg`]);
    await page.locator('[title="Zuschneiden"]').nth(1).waitFor({ timeout: 30000 });
    await shot('1-create');
    await page.getByRole('button', { name: 'Erstellen' }).click();
    await page.waitForURL('**/administration/project/index', { timeout: 15000 });
    await toastOrIdle();
    id = sql("select id from projects where title='QA Testprojekt' order by id desc limit 1");
    slug = sql(`select slug from projects where id=${id}`);
    ok('create project', !!id, `id ${id}, slug ${slug}`);
    ok('  images attached', sql(`select count(*) from images where imageable_id=${id} and imageable_type like '%Project' and deleted_at is null`) === '2');
    ok('  categories/states', sql(`select count(*) from category_project where project_id=${id}`) === '1' && sql(`select count(*) from project_state where project_id=${id}`) === '1');
    const text = sql(`select text from projects where id=${id}`);
    ok('  link saved', /href="https:\/\/www\.example\.com"/.test(text) && /target="_blank"/.test(text), text);
    flushErrors('create');

    // 2 Public page
    let p = await pub(`/projekte/gebaut/wohnen/${slug}`);
    ok('public project page', p.status === 200 && p.html.includes('QA Testprojekt') && p.html.includes('https://www.example.com'), `status ${p.status}`);
    const imgNames = sql(`select name from images where imageable_id=${id} and imageable_type like '%Project' order by \`order\`, id`).split('\n');
    p = await pub('/werkliste');
    ok('in Werkliste', p.html.includes('QA Testprojekt'));

    // 3 Edit: caption + cover on image 1, crop image 2, hide image 2
    await page.goto(`${base}/administration/project/edit/${id}`, { waitUntil: 'networkidle' });
    await page.getByRole('button', { name: 'Bilder', exact: true }).click();
    const card = n => page.locator('.cursor-grab').nth(n);
    await card(0).hover();
    await card(0).locator('[title="Bearbeiten"]').click();
    await page.locator('#image_caption').fill('QA Legende');
    await page.getByText('Cover', { exact: true }).last().click();
    await page.getByRole('button', { name: 'Speichern' }).click();
    await toastOrIdle();
    ok('image caption + cover', sql(`select concat(caption,'|',cover) from images where name='${imgNames[0]}'`) === 'QA Legende|1');
    ok('  cover image on the public page', (await pub(`/projekte/gebaut/wohnen/${slug}`)).html.includes(imgNames[0]));
    await card(1).hover();
    await card(1).locator('[title="Zuschneiden"]').click();
    await page.waitForTimeout(1500);
    await shot('3-crop');
    // move the crop area a bit with the keyboard-free way: drag inside the cropper
    const area = page.locator('.vue-advanced-cropper__stretcher, .vue-advanced-cropper').first();
    const box = await area.boundingBox();
    if (box) {
      await page.mouse.move(box.x + box.width * 0.9, box.y + box.height * 0.9);
      await page.mouse.down(); await page.mouse.move(box.x + box.width * 0.6, box.y + box.height * 0.6, { steps: 8 }); await page.mouse.up();
    }
    await page.getByRole('button', { name: 'Speichern' }).last().click();
    await toastOrIdle();
    ok('crop saved', sql(`select coalesce(coords_w,'')<>'' from images where name='${imgNames[1]}'`) === '1', sql(`select concat_ws(',',coords_w,coords_h,coords_x,coords_y) from images where name='${imgNames[1]}'`));
    await card(1).hover();
    await card(1).locator('[title="Verbergen"]').click();
    await toastOrIdle();
    ok('hide image', sql(`select publish from images where name='${imgNames[1]}'`) === '0');
    // reorder images: drag 2nd before 1st
    { const a = await card(1).boundingBox(), b = await card(0).boundingBox();
      await page.mouse.move(a.x + a.width / 2, a.y + a.height / 3); await page.mouse.down();
      await page.mouse.move(a.x + a.width / 2 - 20, a.y + a.height / 3, { steps: 5 });
      const saved = page.waitForResponse(r => r.url().includes('/order'), { timeout: 5000 }).catch(() => null);
      await page.mouse.move(b.x + 10, b.y + b.height / 3, { steps: 20 }); await page.waitForTimeout(300); await page.mouse.up();
      await saved; await toastOrIdle(); }
    ok('reorder images (drag)', sql(`select name from images where imageable_id=${id} and imageable_type like '%Project' order by \`order\` limit 1`) === imgNames[1]);
    await shot('3-images');
    await page.getByRole('button', { name: 'Aktualisieren' }).click();
    await page.waitForURL('**/administration/project/index', { timeout: 15000 });
    flushErrors('edit');
    p = await pub(`/projekte/gebaut/wohnen/${slug}`);
    ok('public: hidden image gone, caption shown', !p.html.includes(imgNames[1]) && p.html.includes('QA Legende'));

    // 4 Unpublish / publish from the list
    await page.goto(base + '/administration/project/index', { waitUntil: 'networkidle' });
    const row = page.locator('tr, li, .listing__item').filter({ hasText: 'QA Testprojekt' }).first();
    await Promise.all([page.waitForResponse(r => r.url().includes('/api/project/toggle/')), row.locator('[title="Veröffentlicht"]').click()]);
    await toastOrIdle();
    ok('unpublish (list)', sql(`select publish from projects where id=${id}`) === '0');
    p = await pub(`/projekte/gebaut/wohnen/${slug}`);
    ok('  public page 404', p.status === 404, `status ${p.status}`);
    ok('  not in Werkliste', !(await pub('/werkliste')).html.includes('QA Testprojekt'));
    await Promise.all([page.waitForResponse(r => r.url().includes('/api/project/toggle/')), row.locator('[title="Nicht veröffentlicht"]').click()]);
    await toastOrIdle();
    ok('publish again', sql(`select publish from projects where id=${id}`) === '1' && (await pub(`/projekte/gebaut/wohnen/${slug}`)).status === 200);
    flushErrors('toggle');

    // 4b Project teaser: the visible image as a teaser in column 1
    await page.goto(`${base}/administration/project/teaser/${id}`, { waitUntil: 'networkidle' });
    await page.getByRole('button', { name: 'Bild', exact: true }).first().click();
    await page.getByRole('dialog').locator('img').first().click();
    await toastOrIdle();
    ok('add project teaser', sql(`select count(*) from teasers where project_id=${id}`) === '1');
    p = await pub(`/projekte/gebaut/wohnen/${slug}`);
    ok('  teaser on the public page', (p.html.match(new RegExp(imgNames[0], 'g')) || []).length > 1 || p.html.includes(imgNames[1]));
    await shot('4b-teaser');
    flushErrors('teaser');

    // 5 Add to the Wohnen overview
    await page.goto(base + '/administration/project/landing/edit/1/category', { waitUntil: 'networkidle' });
    await page.getByRole('button', { name: 'Projekt' }).first().click();
    await page.waitForTimeout(800);
    await page.getByRole('navigation', { name: 'Projekte' }).getByText('QA Testprojekt').first().click();
    await page.waitForTimeout(500);
    await shot('5-picker');
    await page.getByRole('dialog').locator('img').first().click();
    await toastOrIdle();
    ok('add to overview', sql(`select count(*) from project_landing where category_id=1 and project_id=${id}`) === '1');
    p = await pub('/projekte/uebersicht/gebaut/wohnen');
    ok('  shown on /projekte/uebersicht/gebaut/wohnen', p.html.includes(slug));
    p = await pub(`/projekte/gebaut/wohnen/${slug}`);
    ok('  project page now has "Nächstes Projekt"', p.html.includes('Nächstes Projekt'));
    flushErrors('landing');

    // 6 Publication with a PDF
    await page.goto(base + '/administration/publication/create', { waitUntil: 'networkidle' });
    await page.locator('input[name=year]').fill('2026');
    await page.locator('textarea[name=title]').fill('QA Publikation');
    await page.getByRole('button', { name: 'Dateien', exact: true }).click().catch(() => {});
    await page.locator('input[type=file]').last().setInputFiles(`${D}/qa-publikation.pdf`);
    await page.locator('[title="Bearbeiten"]').last().waitFor({ timeout: 15000 });
    await shot('6-publication');
    await page.getByRole('button', { name: 'Erstellen' }).click();
    await page.waitForURL('**/administration/publication/index', { timeout: 15000 });
    const pubId = sql("select id from publications where title='QA Publikation' order by id desc limit 1");
    const fileName = sql(`select name from files where fileable_id=${pubId} and deleted_at is null limit 1`);
    ok('create publication with PDF', !!pubId && !!fileName, `${pubId} ${fileName}`);
    p = await pub('/buero/publikationen');
    ok('  listed on /buero/publikationen with PDF link', p.html.includes('QA Publikation') && p.html.includes(`/storage/uploads/${fileName}`));
    const pdf = await page.context().request.get(`${base}/storage/uploads/${fileName}`);
    ok('  PDF downloads', pdf.status() === 200 && (await pdf.body()).slice(0, 4).toString() === '%PDF', `status ${pdf.status()}`);
    flushErrors('publication');

    // 7 Delete both
    await page.goto(base + '/administration/publication/index', { waitUntil: 'networkidle' });
    await page.locator('tr, li, .listing__item').filter({ hasText: 'QA Publikation' }).first().locator('[title="Löschen"]').click();
    await page.getByRole('button', { name: 'Löschen' }).last().click();
    await toastOrIdle();
    ok('delete publication', sql(`select count(*) from publications where id=${pubId}`) === '0' || sql(`select deleted_at is not null from publications where id=${pubId}`) === '1');
    ok('  gone from /buero/publikationen', !(await pub('/buero/publikationen')).html.includes('QA Publikation'));
    await page.goto(base + '/administration/project/index', { waitUntil: 'networkidle' });
    await page.locator('tr, li, .listing__item').filter({ hasText: 'QA Testprojekt' }).first().locator('[title="Löschen"]').click();
    await page.getByRole('button', { name: 'Löschen' }).last().click();
    await toastOrIdle();
    ok('delete project', sql(`select count(*) from projects where id=${id}`) === '0');
    ok('  public page 404', (await pub(`/projekte/gebaut/wohnen/${slug}`)).status === 404);
    ok('  gone from overview', !(await pub('/projekte/uebersicht/gebaut/wohnen')).html.includes(slug));
    ok('  gone from Werkliste', !(await pub('/werkliste')).html.includes('QA Testprojekt'));
    ok('  its overview place and teasers gone', sql(`select count(*) from project_landing where project_id=${id}`) === '0' && sql(`select count(*) from teasers where project_id=${id}`) === '0');
    flushErrors('delete');
  } catch (e) {
    ok('script', false, e.message.split('\n')[0]);
    await shot('fail');
    console.log('screenshots in', D);
  }
  await browser.close();
  console.log(`\n${results.filter(r => r.startsWith('OK')).length}/${results.length} OK`);
})();
