const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const moduleSource = read('vetra-plus/modules/elementor/class-module.php');
const renderer = read('vetra-plus/modules/elementor/class-renderer.php');
const grid = read('vetra-plus/modules/elementor/class-project-grid.php');
const gallery = read('vetra-plus/modules/elementor/class-project-gallery.php');
const banner = read('vetra-plus/modules/elementor/class-corporate-banner.php');
const css = read('vetra-plus/assets/css/vetra-elements.css');
const plugin = read('vetra-plus/includes/class-plugin.php');
const loader = read('vetra-plus/includes/class-loader.php');

test('Elementor module registers the Vetra Elements category and modern widget hook', () => {
  assert.match(moduleSource, /elementor\/elements\/categories_registered/);
  assert.match(moduleSource, /vetra-elements/);
  assert.match(moduleSource, /eicon-building/);
  assert.match(moduleSource, /elementor\/widgets\/register/);
  assert.match(moduleSource, /new ProjectGrid/);
  assert.match(moduleSource, /new ProjectGallery/);
  assert.match(moduleSource, /new CorporateBanner/);
  assert.match(plugin, /Elementor\\Module/);
});

test('three widgets use official Elementor controls and dynamic dependencies', () => {
  for (const source of [grid, gallery, banner]) {
    assert.match(source, /extends \\Elementor\\Widget_Base/);
    assert.match(source, /get_categories\(\): array/);
    assert.match(source, /Controls_Manager/);
    assert.match(source, /Renderer::/);
  }
  assert.match(grid, /get_style_depends\(\): array/);
  assert.match(gallery, /get_script_depends\(\): array/);
  assert.match(banner, /Controls_Manager::URL/);
});

test('shortcode fallbacks and conditional assets are wired without Elementor dependency', () => {
  for (const shortcode of ['vetra_projects', 'vetra_project_gallery', 'vetra_corporate_banner']) {
    assert.match(moduleSource, new RegExp(`add_shortcode\\( '${shortcode}'`));
  }
  assert.match(moduleSource, /wp_register_style\( 'vetra-plus-elements'/);
  assert.match(moduleSource, /wp_register_script\( 'vetra-plus-elements'/);
  assert.match(renderer, /wp_enqueue_style\( 'vetra-plus-elements' \)/);
  assert.match(renderer, /wp_enqueue_script\( 'vetra-plus-elements' \)/);
  assert.match(loader, /Elementor\\\\ProjectGrid/);
});

test('Elementor assets are RTL-aware, responsive, and free of forbidden builders', () => {
  assert.match(css, /inset-block-start/);
  assert.match(css, /@media/);
  assert.match(css, /#12161a/);
  assert.match(css, /#c67d34/);
  assert.doesNotMatch([moduleSource, renderer, grid, gallery, banner, css].join('\n'), /WooCommerce|WPBakery|Codevz|wc_/i);
});
