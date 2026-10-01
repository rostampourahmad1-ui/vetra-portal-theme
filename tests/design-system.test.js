const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const exists = (name) => fs.existsSync(path.join(root, name));

const tokens = read('assets/css/vetra-tokens.css');
const components = read('assets/css/vetra-components.css');
const utilities = read('assets/css/vetra-utilities.css');
const print = read('assets/css/vetra-print.css');
const script = read('assets/js/vetra-ui.js');
const loader = read('inc/vetra-design-system.php');
const bootstrap = read('functions.php');
const themeJson = read('theme.json');
const readme = read('readme.md');
const preview = read('templates/design-system.php');

const OFFICIAL_HEX = ['#12161A', '#C67D34', '#8A95A5', '#F6F7F9', '#B3803B', '#222933'];

test('design system ships all required asset and loader files', () => {
  for (const file of [
    'assets/css/vetra-tokens.css',
    'assets/css/vetra-components.css',
    'assets/css/vetra-utilities.css',
    'assets/css/vetra-print.css',
    'assets/js/vetra-ui.js',
    'inc/vetra-design-system.php',
    'templates/design-system.php',
    'docs/DESIGN-SYSTEM.md',
  ]) {
    assert.ok(exists(file), `missing ${file}`);
  }
});

test('the official palette is defined verbatim and never augmented', () => {
  for (const hex of OFFICIAL_HEX) {
    assert.ok(tokens.toUpperCase().includes(hex), `tokens missing ${hex}`);
  }
  assert.equal((tokens.match(/#F97316/gi) || []).length, 0, 'forbidden orange leaked into tokens');
});

test('forbidden colour #F97316 is absent from the whole design system', () => {
  for (const [name, contents] of Object.entries({
    tokens,
    components,
    utilities,
    print,
    script,
    loader,
    themeJson,
    preview,
  })) {
    assert.equal((contents.match(/#F97316/gi) || []).length, 0, `forbidden colour in ${name}`);
  }
});

test('semantic colour tokens are all exposed', () => {
  for (const token of [
    '--vetra-color-bg',
    '--vetra-color-surface',
    '--vetra-color-surface-raised',
    '--vetra-color-text',
    '--vetra-color-text-muted',
    '--vetra-color-border',
    '--vetra-color-primary',
    '--vetra-color-primary-hover',
    '--vetra-color-focus',
    '--vetra-color-critical',
    '--vetra-color-success',
    '--vetra-color-warning',
    '--vetra-color-danger',
  ]) {
    assert.ok(tokens.includes(token + ':'), `missing semantic token ${token}`);
  }
});

test('light, dark and manual + OS colour modes are supported', () => {
  assert.match(tokens, /\[data-vetra-theme="dark"\]/);
  assert.match(tokens, /:root:not\(\[data-vetra-theme="light"\]\)/);
  assert.match(tokens, /prefers-color-scheme:\s*dark/);
  assert.match(tokens, /prefers-reduced-motion/);
  assert.match(script, /vetra-theme-mode/);
  assert.match(script, /setTheme/);
});

test('radius is capped and shadows stay subtle', () => {
  assert.match(tokens, /--vetra-radius-control:\s*6px/);
  assert.match(tokens, /--vetra-radius-md:\s*8px/);
  const radii = [...components.matchAll(/border-radius:\s*(\d+)px/g)].map((m) => Number(m[1]));
  for (const value of radii) {
    assert.ok(value <= 8, `border-radius ${value}px exceeds the 8px cap`);
  }
  assert.doesNotMatch(components, /backdrop-filter/);
  assert.doesNotMatch(components, /text-shadow/);
});

test('every required component has a namespaced class', () => {
  for (const cls of [
    '.vetra-btn--primary',
    '.vetra-btn--secondary',
    '.vetra-btn--ghost',
    '.vetra-btn--danger',
    '.vetra-input',
    '.vetra-select',
    '.vetra-textarea',
    '.vetra-check',
    '.vetra-radio-group',
    '.vetra-switch',
    '.vetra-table',
    '.vetra-card',
    '.vetra-badge',
    '.vetra-alert',
    '.vetra-toast',
    '.vetra-modal',
    '.vetra-tooltip',
    '.vetra-spinner',
    '.vetra-empty',
    '.vetra-pagination',
    '.vetra-tabs',
    '.vetra-breadcrumb',
    '.vetra-toolbar',
    '.vetra-field--invalid',
    '.vetra-field--valid',
    '.vetra-skeleton',
  ]) {
    assert.ok(components.includes(cls), `missing component ${cls}`);
  }
});

test('RTL is native and logical properties are used', () => {
  assert.match(components, /direction:\s*rtl/);
  assert.match(components, /padding-inline|margin-inline|inset-inline/);
  assert.match(utilities, /margin-block-start/);
});

test('print styles are light, scoped and RTL-aware', () => {
  assert.match(print, /@media print/);
  assert.match(print, /tabular-nums/);
  assert.match(print, /direction:\s*rtl/);
  assert.match(print, /#FFFFFF/);
});

test('UI script is dependency-free and exposes the public API', () => {
  assert.match(script, /window\.VetraUI\s*=/);
  for (const api of ['setTheme', 'getTheme', 'toast', 'openModal', 'closeModal', 'announce']) {
    assert.ok(script.includes(api), `script missing ${api}`);
  }
  assert.doesNotMatch(script, /import\s|require\(/);
});

test('PHP loader is guarded, escaped and plugin-independent', () => {
  assert.match(loader, /if \( ! defined\( 'ABSPATH' \) \)/);
  assert.match(loader, /wp_enqueue_style/);
  assert.match(loader, /wp_enqueue_script/);
  assert.match(loader, /wp_localize_script/);
  assert.match(loader, /VETRA_DS_VERSION', '1\.0\.0'/);
  assert.match(loader, /vetra-badge--tokens|vetra-tokens\.css/);
  assert.match(loader, /vetra-print\.css/);
  assert.match(loader, /esc_html\(/);
  assert.match(loader, /esc_attr\(/);
  assert.match(loader, /esc_url\(/);
  assert.match(loader, /sanitize_html_class/);
  assert.doesNotMatch(loader, /Elementor|GFForms/i);
});

test('bootstrap wires the design system and versions agree', () => {
  assert.match(bootstrap, /inc\/vetra-design-system\.php/);
  const version = bootstrap.match(/VETRA_PORTAL_VERSION', '([^']+)'/)[1];
  assert.equal(read('style.css').match(/^Version: (.+)$/m)[1], version);
  assert.match(readme, /Design System/);
  assert.ok(readme.includes(version), `readme does not mention version ${version}`);
});

test('theme.json uses only the official palette', () => {
  for (const hex of OFFICIAL_HEX) {
    assert.ok(themeJson.includes(hex), `theme.json missing ${hex}`);
  }
  assert.equal((themeJson.match(/#f28b38/i) || []).length, 0, 'legacy accent still present');
  assert.equal((themeJson.match(/#F97316/gi) || []).length, 0, 'forbidden colour in theme.json');
});

test('preview template is registered and documents the components', () => {
  assert.match(loader, /templates\/design-system\.php/);
  assert.match(preview, /Template Name: وترا: پیش‌نمایش دیزاین‌سیستم/);
  assert.match(preview, /data-vetra-theme-toggle/);
  assert.match(preview, /data-vetra-modal-open/);
  assert.match(preview, /vetra-table__num/);
});
