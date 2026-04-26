/**
 * Run inside a Figma plugin (figma global available). Creates one Page per Moodle screen.
 * See comment block at top of file in repo for how to run.
 */
(async function createBaitAlGahwaMoodlePages() {
  const spec = [
    { name: '00 · Design tokens' },
    { name: '01 · Login' },
    { name: '02 · Site home / Hero' },
    { name: '03 · My dashboard' },
    { name: '04 · Course catalog' },
    { name: '05 · Course home' },
    { name: '06 · Activity / incourse' },
    { name: '07 · Navbar' },
    { name: '08 · Footer' },
    { name: '09 · Admin / reports' },
    { name: '10 · Mobile key screens' }
  ];
  const existing = new Set(figma.root.children.map((p) => p.name));
  const created = [];
  let bold = { family: 'Inter', style: 'Bold' };
  let regular = { family: 'Inter', style: 'Regular' };
  try {
    await figma.loadFontAsync(bold);
    await figma.loadFontAsync(regular);
  } catch (e) {
    bold = { family: 'Roboto', style: 'Bold' };
    regular = { family: 'Roboto', style: 'Regular' };
    await figma.loadFontAsync(bold);
    await figma.loadFontAsync(regular);
  }
  for (const item of spec) {
    if (existing.has(item.name)) continue;
    const page = figma.createPage();
    page.name = item.name;
    created.push(page);
    const frame = figma.createFrame();
    frame.name = 'Desktop 1440 — start here';
    frame.resize(1440, 1000);
    frame.fills = [{ type: 'SOLID', color: { r: 0.98, g: 0.96, b: 0.92 } }];
    page.appendChild(frame);
    const title = figma.createText();
    await figma.loadFontAsync(bold);
    title.fontName = bold;
    title.characters = item.name;
    title.fontSize = 22;
    title.fills = [{ type: 'SOLID', color: { r: 0.24, g: 0.14, b: 0.08 } }];
    title.x = 40;
    title.y = 40;
    frame.appendChild(title);
    const body = figma.createText();
    await figma.loadFontAsync(regular);
    body.fontName = regular;
    body.characters =
      'Bait Al Gahwa (Moodle theme) — design this screen. Tokens: #3d2314, #6b4423, #c9a227, #f5e6d3. Code: /theme/baitalgahwa';
    body.fontSize = 14;
    body.fills = [{ type: 'SOLID', color: { r: 0.35, g: 0.3, b: 0.28 } }];
    body.x = 40;
    body.y = 80;
    body.resize(1300, 200);
    frame.appendChild(body);
  }
  if (created.length) {
    await figma.setCurrentPageAsync(created[0]);
  }
  figma.notify('Bait Al Gahwa: created ' + created.length + ' new page(s). Skipped if name already exists.');
})();
