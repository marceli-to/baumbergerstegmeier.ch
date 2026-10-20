// Full-page screenshots of every QA page at 1440 and 390 px; logs console
// errors and 4xx/5xx. Run from a dir with playwright installed:
//   NODE_TLS_REJECT_UNAUTHORIZED=0 NODE_PATH=/tmp/bs-qa/node_modules node shots.js <outdir>
// (Herd's certificate is self-signed)
// Compare two runs: compare.sh <dirA> <dirB>
const { chromium } = require('playwright');
const { base, pages } = require('./pages');
const out = process.argv[2];

(async () => {
  require('fs').mkdirSync(out, { recursive: true });
  const list = await pages();
  const b = await chromium.launch();
  for (const w of [1440, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    const errs = [];
    p.on('pageerror', e => errs.push(e.message));
    p.on('console', m => m.type() === 'error' && errs.push(m.text()));
    p.on('response', r => r.status() >= 400 && errs.push(r.status() + ' ' + r.url()));
    for (const path of list) {
      await p.goto(base + path, { waitUntil: 'networkidle' });
      // Scroll through, so lazy images load
      await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 400) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 150)); } window.scrollTo(0, 0); });
      await p.waitForLoadState('networkidle');
      await p.waitForTimeout(800);
      await p.screenshot({ path: `${out}/${w}${path.replace(/\//g, '_') || '_'}.png`, fullPage: true });
    }
    console.log(w, 'errors:', errs.length ? errs : 'none');
    await ctx.close();
  }
  await b.close();
})();
