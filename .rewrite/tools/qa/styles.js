// Computed style of every element on every QA page (JS off, 1440 and
// 390 px), with the page's own CSS and with a reference CSS swapped in;
// prints the elements/properties that differ.
//   NODE_TLS_REJECT_UNAUTHORIZED=0 NODE_PATH=/tmp/bs-qa/node_modules node styles.js <reference.css>
const { chromium } = require('playwright');
const fs = require('fs');
const { base, pages } = require('./pages');
const ref = fs.readFileSync(process.argv[2], 'utf8');

const snapshot = (p) => p.evaluate(() => [...document.querySelectorAll('body *:not(link):not(script):not(style)')].map((el, i) => {
  const cs = getComputedStyle(el);
  const props = {};
  for (const name of cs) props[name] = cs.getPropertyValue(name);
  return [`${i} ${el.tagName.toLowerCase()}.${[...el.classList].join('.')}`, props];
}));

(async () => {
  const list = await pages();
  const b = await chromium.launch();
  let total = 0;
  for (const w of [1440, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, ignoreHTTPSErrors: true, javaScriptEnabled: false });
    for (const path of list) {
      const p = await ctx.newPage();
      await p.goto(base + path, { waitUntil: 'load' });
      const mine = await snapshot(p);
      await p.evaluate((css) => {
        document.querySelectorAll('head link[rel=stylesheet][href*="/build/"]').forEach((l) => l.remove());
        const s = document.createElement('style');
        s.textContent = css;
        document.head.appendChild(s);
      }, ref);
      await p.waitForTimeout(300);
      const theirs = await snapshot(p);
      const diffs = [];
      mine.forEach(([key, props], i) => {
        const other = theirs[i][1];
        for (const name in props) {
          // A reference injected as <style> resolves relative url()s against the page
          const norm = (v) => (v || "").replace(/url\("[^"]*\/(img\/icons\/[^"]+)"\)/g, "url($1)").replace(/url\("[^"]*\/(fonts\/[^"]+)"\)/g, "url($1)");
          if (norm(props[name]) !== norm(other[name])) diffs.push(`${key} ${name}: ${other[name]} → ${props[name]}`);
        }
      });
      total += diffs.length;
      if (diffs.length) console.log(`${w} ${path}: ${diffs.length}\n  ` + diffs.slice(0, 8).join('\n  '));
      await p.close();
    }
    await ctx.close();
  }
  console.log('differences:', total);
  await b.close();
})();
