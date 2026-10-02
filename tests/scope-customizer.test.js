const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const exists = (name) => fs.existsSync(path.join(root, name));

test('native Customizer is enabled and preserves valid direct routes', () => {
  const customizer = read('inc/customizer.php');
  const bootstrap = read('functions.php');
  assert.match(customizer, /add_action\( 'customize_register', 'vetra_customizer_register'/);
  assert.doesNotMatch(customizer, /load-customize\.php[\s\S]*wp_safe_redirect/);
  assert.doesNotMatch(customizer, /vetra_disable_customizer/);
  const guard = bootstrap.slice(bootstrap.indexOf('function vetra_restrict_dashboard'), bootstrap.indexOf("add_action( 'admin_init', 'vetra_restrict_dashboard'") );
  assert.doesNotMatch(guard, /'customize\.php' === \$pagenow/);
  assert.match(guard, /'customize\.php' !== \$pagenow/);
});

test('Projects are absent from active theme and Vetra Plus runtime surfaces', () => {
  assert.ok(!exists('inc/projects.php'));
  assert.ok(!exists('vetra-plus/includes/class-post-types.php'));
  assert.ok(!exists('vetra-plus/includes/class-api.php'));
  assert.doesNotMatch(read('functions.php'), /inc\/projects\.php|vetra_projects|vetra_project/);
  assert.doesNotMatch(read('front-page.php'), /home_projects_enabled|vetra_featured_projects|vetra_project/);
  assert.doesNotMatch(read('inc/customizer.php'), /home_projects_enabled|projects_title|project_[123]_/);
  assert.doesNotMatch(read('vetra-plus/includes/class-plugin.php'), /PostTypes|MetaBoxes|Capabilities|API/);
  assert.doesNotMatch(read('vetra-plus/modules/elementor/class-module.php'), /vetra_projects|ProjectGrid|ProjectGallery/);
});

test('Vetra Plus settings are an Appearance submenu, not a top-level menu', () => {
  const settings = read('vetra-plus/modules/settings/class-settings.php');
  assert.match(settings, /add_submenu_page\( 'themes\.php'/);
  assert.doesNotMatch(settings, /add_menu_page\(/);
  assert.match(settings, /vetra-plus-settings/);
});

test('Commerce integrations are absent from VETRA runtime code', () => {
  const files = ['functions.php', 'inc/customizer.php', 'inc/integrations.php', 'vetra-plus/includes/class-plugin.php', 'vetra-plus/modules/settings/class-settings.php'];
  for (const file of files) assert.doesNotMatch(read(file), /WooCommerce|is_product|is_woocommerce|wc_/i);
});
