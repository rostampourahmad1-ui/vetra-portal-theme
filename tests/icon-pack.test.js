const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const exists = (name) => fs.existsSync(path.join(root, name));

const library = JSON.parse(read('assets/icons/vetra-icons.json'));
const icons = library.icons;
const names = Object.keys(icons);

const css = read('assets/css/vetra-icons.css');
const renderer = read('inc/vetra-icons.php');
const loader = read('inc/vetra-icon-library.php');
const sprite = read('assets/icons/vetra-icons.svg');
const bootstrap = read('functions.php');
const helpers = read('inc/helpers.php');

const REQUIRED = [
  'blueprint', 'building-crane', 'construction-site', 'building', 'safety-helmet',
  'contract', 'document', 'folder-project', 'engineer', 'settings', 'search',
  'filter', 'export', 'print', 'close', 'check', 'warning', 'info',
  'arrow-left', 'arrow-right', 'chevron-down', 'menu', 'logout',
  'dashboard-grid', 'id-card', 'user-profile', 'ticket-support', 'wallet-safe',
  'payment-card', 'bank-transfer', 'bell-notification', 'message',
  'lock-security', 'login', 'upload-receipt',
  'rebar-bundle', 'cut-blade', 'cut-plan', 'warehouse-rack', 'stock-material',
  'scrap-recycling', 'usable-remnant', 'waste-material', 'measurement',
  'optimization', 'calculation', 'material-report',
  'gantt-chart', 'wbs-nodes', 'task', 'task-complete', 'milestone-diamond',
  'critical-path', 'calendar', 'timeline-day', 'timeline-week', 'timeline-month',
  'zoom-in', 'zoom-out', 'today', 'dependency', 'project-progress',
];

test('all icon pack files ship', () => {
  for (const file of [
    'inc/vetra-icons.php',
    'inc/vetra-icon-library.php',
    'assets/icons/vetra-icons.json',
    'assets/icons/vetra-icons.svg',
    'assets/css/vetra-icons.css',
    'tools/build-icon-sprite.js',
    'docs/ICON-PACK.md',
  ]) {
    assert.ok(exists(file), `missing ${file}`);
  }
});

test('every required icon exists in the library', () => {
  for (const name of REQUIRED) {
    assert.ok(names.includes(name), `missing icon ${name}`);
  }
});

test('icon geometry uses the 24x24 / 1.5 contract and safe tags only', () => {
  assert.equal(library.viewBox, '0 0 24 24');
  assert.equal(library.strokeWidth, 1.5);

  const allowed = ['path', 'circle', 'rect', 'line', 'polyline', 'polygon', 'g'];
  const blocked = /script|onload|onerror|onclick|javascript:|xlink:|href=|<image|foreignobject|<use/i;

  for (const [name, icon] of Object.entries(icons)) {
    assert.ok(Array.isArray(icon.base) && icon.base.length > 0, `${name} has no base geometry`);
    for (const element of [...icon.base, ...(icon.accent || [])]) {
      const match = element.match(/^<([a-zA-Z]+)(\s[^>]*)?\/?>$/);
      assert.ok(match, `${name} has malformed element: ${element}`);
      assert.ok(allowed.includes(match[1].toLowerCase()), `${name} uses disallowed tag ${match[1]}`);
      assert.doesNotMatch(element, blocked, `${name} contains unsafe markup`);
      assert.doesNotMatch(element, /\son[a-z]+\s*=/i, `${name} contains an event handler`);
    }
  }
});

test('aliases resolve to real icons', () => {
  for (const [alias, target] of Object.entries(library.aliases)) {
    assert.ok(names.includes(target), `alias ${alias} -> unknown ${target}`);
    assert.ok(!names.includes(alias), `alias ${alias} collides with a canonical icon`);
  }
});

test('CSS defines the required classes and geometry', () => {
  for (const cls of ['.vetra-icon', '.vetra-icon-base', '.vetra-icon-accent', '.vetra-icon-base-fill', '.vetra-icon-accent-fill']) {
    assert.ok(css.includes(cls), `missing ${cls}`);
  }
  assert.match(css, /width:\s*1\.5em/);
  assert.match(css, /height:\s*1\.5em/);
  assert.match(css, /stroke-width:\s*1\.5/);
  assert.match(css, /stroke-linecap:\s*square/);
  assert.match(css, /stroke-linejoin:\s*miter/);
  assert.match(css, /\.vetra-icon-base\s*\{[^}]*stroke:\s*currentColor/s);
  assert.match(css, /\.vetra-icon-accent\s*\{[^}]*stroke:\s*#C67D34/s);
  assert.match(css, /\[dir="rtl"\]\s*\.vetra-icon--directional/);
});

test('forbidden colour is absent from the icon pack', () => {
  for (const [name, contents] of Object.entries({ css, renderer, loader, sprite, json: JSON.stringify(library) })) {
    assert.equal((contents.match(/#F97316/gi) || []).length, 0, `forbidden colour in ${name}`);
  }
});

test('PHP renderer whitelists names, escapes output and never breaks', () => {
  assert.match(renderer, /if \( ! defined\( 'ABSPATH' \) \)/);
  assert.match(renderer, /function vetra_icon\(/);
  assert.match(renderer, /function vetra_inline_icon\(/);
  assert.match(renderer, /function vetra_icon_shortcode\(/);
  assert.match(renderer, /add_shortcode\( 'vetra_icon'/);
  assert.match(renderer, /function vetra_icon_data_uri\(/);
  assert.match(renderer, /vetra_icon_resolve_name\(/);
  assert.match(renderer, /VETRA_ICON_FALLBACK/);
  assert.match(renderer, /esc_attr\(/);
  assert.match(renderer, /esc_html\(/);
  assert.match(renderer, /sanitize_text_field\(/);
  assert.match(renderer, /sanitize_html_class\(/);
});

test('library validates geometry and colour and exposes the sprite', () => {
  assert.match(loader, /function vetra_icon_library\(/);
  assert.match(loader, /function vetra_icon_validate_markup\(/);
  assert.match(loader, /function vetra_icon_sanitize_color\(/);
  assert.match(loader, /function vetra_icon_sprite_markup\(/);
  assert.match(loader, /function vetra_icon_resolve_name\(/);
  assert.match(loader, /json_decode/);
  assert.match(loader, /foreignobject/);
  assert.match(loader, /javascript:/);
});

test('sprite contains a symbol for every icon and is generated', () => {
  const ids = [...sprite.matchAll(/<symbol id="vetra-icon-([a-z0-9-]+)"/g)].map((m) => m[1]);
  assert.equal(ids.length, names.length, 'sprite symbol count differs from library');
  for (const name of names) {
    assert.ok(ids.includes(name), `sprite missing ${name}`);
  }
  assert.match(read('tools/build-icon-sprite.js'), /vetra-icons\.json/);
});

test('bootstrap loads the icon pack and helpers delegate to it', () => {
  assert.match(bootstrap, /inc\/vetra-icon-library\.php/);
  assert.match(bootstrap, /inc\/vetra-icons\.php/);
  assert.match(helpers, /vetra_icon\(\)/);
  assert.doesNotMatch(helpers, /function vetra_inline_icon\(/);
});

test('removed project module is not loaded by the theme', () => {
  assert.doesNotMatch(read('functions.php'), /inc\/projects\.php/);
  assert.ok(!exists('inc/projects.php'));
});

test('the design system preview documents the icon pack', () => {
  assert.match(read('templates/design-system.php'), /vetra_icon\(|\[vetra_icon/);
});
