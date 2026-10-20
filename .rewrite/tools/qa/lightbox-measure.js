// Opens the gallery on a project page and on the home page's teasers at
// 1440 and 390 px and prints where the image, caption, counter and
// buttons are, so the own lightbox can be measured against fancyBox 5.
// Screenshots to OUT (default /tmp).
//   NODE_TLS_REJECT_UNAUTHORIZED=0 NODE_PATH=/tmp/bs-qa/node_modules node lightbox-measure.js <label>
const { chromium } = require('playwright');
const { base, pages } = require('./pages');
const label = process.argv[2] || 'run';
const out = process.env.OUT || '/tmp';

const measure = (p) => p.evaluate(() => {
  const box = (sel) => {
    const els = [...document.querySelectorAll(sel)].filter((e) => { const r = e.getBoundingClientRect(); return r.width && r.height && r.right > 0 && r.left < innerWidth && getComputedStyle(e).opacity !== '0' && getComputedStyle(e).visibility !== 'hidden'; });
    if (!els.length) return '-';
    const r = els[els.length - 1].getBoundingClientRect();
    return `${Math.round(r.left)},${Math.round(r.top)} ${Math.round(r.width)}×${Math.round(r.height)}`;
  };
  const text = (sel) => { const e = document.querySelector(sel); return e ? e.innerText.replace(/\s+/g, ' ').trim() : '-'; };
  return [
    `image   ${box('.fancybox__slide.is-selected img, .lightbox__stage img.is-current')} natural ${(() => { const i = document.querySelector('.fancybox__slide.is-selected img, .lightbox__stage img.is-current'); return i ? i.naturalWidth + '×' + i.naturalHeight : '-'; })()}`,
    `caption ${box('.fancybox__caption, .lightbox__caption')} "${text('.fancybox__slide.is-selected .fancybox__caption, .fancybox__caption, .lightbox__caption')}"`,
    `close   ${box('[data-fancybox-close], .lightbox__close')}`,
    `prev    ${box('.fancybox__nav .is-prev, .lightbox__prev')}`,
    `next    ${box('.fancybox__nav .is-next, .lightbox__next')}`,
  ].join('\n');
});

(async () => {
  const list = await pages();
  const project = list.find((p) => p.startsWith('/projekte/gebaut/'));
  const b = await chromium.launch();
  for (const w of [1440, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    for (const path of [project, ...(process.env.PAGES ? process.env.PAGES.split(',') : [])]) {
      await p.goto(base + path, { waitUntil: 'networkidle' });
      const groups = await p.evaluate(() => [...document.querySelectorAll('[data-fancybox], [data-lightbox]')].map((a) => a.dataset.fancybox || a.dataset.lightbox));
      console.log(`=== ${w} ${path}: ${groups.length} links, groups ${[...new Set(groups)].join(' ')}`);
      if (!groups.length) continue;
      const link = p.locator('[data-fancybox]:visible, [data-lightbox]:visible').first();
      await link.scrollIntoViewIfNeeded();
      await link.click();
      await p.waitForTimeout(1500);
      console.log('--- open\n' + await measure(p));
      await p.screenshot({ path: `${out}/lightbox-${label}-${w}${path.replace(/\//g, '_')}-1.png` });
      await p.keyboard.press('ArrowRight');
      await p.waitForTimeout(1500);
      console.log('--- next\n' + await measure(p));
      await p.screenshot({ path: `${out}/lightbox-${label}-${w}${path.replace(/\//g, '_')}-2.png` });
      await p.keyboard.press('Escape');
      await p.waitForTimeout(800);
      console.log('--- esc\n' + (await p.evaluate(() => !!document.querySelector('.fancybox__container, .lightbox.is-open')) ? 'still open' : 'closed'));
    }
    await ctx.close();
  }
  await b.close();
})();
