const puppeteer = require('puppeteer');

const keywords = {
  'efcc': 95,
  'fraud': 90,
  'scam': 85,
  'syndicate': 80,
  'arrest': 80,
  'money laundering': 85,
  'cybercrime': 80,
  'billion': 75,
  'bribe': 75,
  'prosecution': 70,
  'suspect': 70,
  'charge': 70,
  'court': 70,
  'transaction': 65,
  'investigation': 65,
  'criminal': 65,
  'nigeria': 50
};

function guessConfidence(text) {
  const content = text.toLowerCase();
  let score = 0;
  let matches = 0;

  for (const [word, weight] of Object.entries(keywords)) {
    if (content.includes(word)) {
      score += weight;
      matches++;
    }
  }

  return matches ? Math.round(score / matches) : 50;
}

(async () => {
  const fullName = process.argv[2];
  if (!fullName) {
    console.error('No name provided');
    process.exit(1);
  }

  const nameParts = fullName.toLowerCase().split(/\s+/);
  const query = encodeURIComponent(`${fullName} efcc fraud scam`);
  const url = `https://www.bing.com/search?q=${query}`;

  const browser = await puppeteer.launch({ headless: true });
  const page = await browser.newPage();

  await page.setUserAgent('CloudspaceAMLBot/1.0 (+https://yourdomain.com/bot)');
  await page.goto(url, { waitUntil: 'domcontentloaded' });

  await new Promise(resolve => setTimeout(resolve, 2000));

  const results = await page.evaluate(() => {
    const articles = [];

    document.querySelectorAll('.b_algo').forEach(result => {
      const titleEl = result.querySelector('h2 a');
      const snippetEl = result.querySelector('.b_caption p');

      const title = titleEl?.innerText.trim();
      const href = titleEl?.href;
      const snippet = snippetEl?.innerText.trim() ?? '';

      if (title && href) {
        articles.push({
          title,
          snippet,
          url: href,
          source: 'BingWebEngine'
        });
      }
    });

    return articles;
  });

  const flagged = results
    .map(r => {
      const content = `${r.title} ${r.snippet}`.toLowerCase();
      const matchedParts = nameParts.filter(part => content.includes(part));
      if (matchedParts.length < 2) return null;

      return {
        source: r.source,
        match_type: 'web result',
        description: `${r.title}${r.snippet ? ' — ' + r.snippet : ''}`,
        confidence: guessConfidence(content),
        source_url: r.url
      };
    })
    .filter(r => r !== null);

  console.log(JSON.stringify(flagged, null, 2));
  await browser.close();
})();
