// Pages the QA scripts visit: the fixed ones plus six project pages
const base = process.env.BASE || 'https://baumbergerstegmeier.ch.test';

async function pages() {
  const html = await (await fetch(base + '/projekte/uebersicht/wettbewerbe')).text();
  const built = await (await fetch(base + '/projekte/uebersicht/gebaut/wohnen')).text();
  const links = (page) => [...new Set([...page.matchAll(/href="(?:https?:\/\/[^"\/]+)?(\/projekte\/[^"]+)"/g)]
    .map(m => m[1]).filter(p => !p.startsWith('/projekte/uebersicht')))];
  const projects = [...links(html).slice(0, 3), ...links(built).slice(0, 3)];
  return [
    '/', '/projekte/uebersicht/in-planung', '/projekte/uebersicht/wettbewerbe', '/projekte/uebersicht/gebaut/wohnen',
    '/werkliste', '/werkliste/jahr', '/werkliste/status/gebaut', '/buero/profil', '/buero/team', '/buero/publikationen',
    '/buero/auszeichnungen', '/buero/jobs', '/kontakt', '/login', ...projects,
  ];
}

module.exports = { base, pages };
