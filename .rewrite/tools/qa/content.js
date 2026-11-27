// Visible text and image files of every page, on two sites, to diff the
// information they show regardless of markup and styling. The URL list is
// the sitemap of the second site (production before the rework has none).
//   NODE_TLS_REJECT_UNAUTHORIZED=0 NODE_PATH=<dir with cheerio>/node_modules \
//     node content.js https://baumbergerstegmeier.ch https://baumbergerstegmeier.ch.test <outdir>
//   diff -r <outdir>/a <outdir>/b
// One request at a time. Each page becomes one file: status, the <body>
// text (one line per text node, scripts and svg left out), then the upload
// file names the page uses (img, srcset, data-*, inline styles), sorted.
const cheerio = require('cheerio');
const fs = require('fs');
const [a, b, out] = process.argv.slice(2);
if (!out) { console.error('usage: node content.js <siteA> <siteB> <outdir>'); process.exit(1); }

const UPLOAD = /[0-9a-f]{13}_[^\/"'?,\s)]+?\.(?:jpe?g|png|gif|webp|svg|pdf)/gi;

function extract(html) {
  const $ = cheerio.load(html);
  $('script, style, noscript, svg, template').remove();
  const lines = [];
  const walk = (node) => {
    for (const c of node.children || []) {
      if (c.type === 'text') {
        const t = c.data.replace(/\s+/g, ' ').trim();
        if (t) lines.push(t);
      } else walk(c);
    }
  };
  walk($('body')[0] || $.root()[0]);
  const images = [...new Set($('body').html()?.match(UPLOAD) || [])].sort();
  return { text: lines.join('\n'), images };
}

(async () => {
  const sitemap = await (await fetch(b.replace(/\/$/, '') + '/sitemap.xml')).text();
  const paths = [...sitemap.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => new URL(m[1]).pathname);
  const summary = [];
  for (const [side, base] of [['a', a], ['b', b]]) {
    fs.mkdirSync(`${out}/${side}`, { recursive: true });
    for (const path of paths) {
      const res = await fetch(base.replace(/\/$/, '') + path, { redirect: 'follow' });
      const { text, images } = extract(await res.text());
      const name = path.replace(/\//g, '_') || '_';
      fs.writeFileSync(`${out}/${side}/${name}.txt`, `status ${res.status}\n\n${text}\n\n-- images\n${images.join('\n')}\n`);
      if (res.status !== 200) summary.push(`${side} ${res.status} ${path}`);
    }
  }
  fs.writeFileSync(`${out}/paths.txt`, paths.join('\n') + '\n');
  console.log(paths.length, 'pages;', summary.length ? summary.join('\n') : 'all 200 on both sides');
})();
