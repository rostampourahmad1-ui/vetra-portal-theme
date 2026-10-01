const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');

test('core theme declares PHP 8.2 and the VETRA visual identity', () => {
  const style = read('style.css');
  assert.match(style, /Requires PHP: 8\.2/);
  for (const colour of ['#12161A', '#C67D34', '#B3803B']) {
    assert.match(style, new RegExp(colour, 'i'));
  }
  assert.match(read('rtl.css'), /direction:\s*rtl|inset-inline/);
});

test('bootstrap loads the guarded VETRA-only class loader with compatibility fallbacks', () => {
  const bootstrap = read('functions.php');
  const loader = read('inc/core/class-loader.php');
  assert.match(bootstrap, /inc\/core\/class-loader\.php/);
  assert.match(bootstrap, /class_exists\( '\\\\Vetra\\\\Theme\\\\Theme' \)/);
  assert.match(loader, /namespace Vetra\\Theme/);
  assert.match(loader, /spl_autoload_register/);
  assert.match(loader, /Vetra\\\\Theme\\\\Admin\\\\Dashboard/);
  assert.match(loader, /is_readable\( \$file \)/);
});

test('core theme keeps optional integrations and forbidden commerce builders absent', () => {
  const source = [
    read('functions.php'),
    read('inc/core/class-loader.php'),
    read('inc/integrations.php'),
    read('inc/core/class-updater.php'),
  ].join('\n');
  assert.doesNotMatch(source, /WooCommerce|WPBakery|Codevz|wc_/i);
  assert.match(source, /ELEMENTOR_VERSION|function_exists\( 'gravity_form' \)/);
});

test('production asset variants exist and readable assets remain available for SCRIPT_DEBUG', () => {
  for (const asset of [
    'assets/js/corporate.min.js',
    'assets/css/corporate.min.css',
    'vetra-plus/assets/js/vetra-elements.min.js',
    'vetra-plus/assets/css/vetra-elements.min.css',
  ]) {
    assert.ok(fs.existsSync(path.join(root, asset)), asset);
  }
  assert.match(read('functions.php'), /SCRIPT_DEBUG/);
  assert.match(read('vetra-plus/modules/elementor/class-module.php'), /SCRIPT_DEBUG/);
});
