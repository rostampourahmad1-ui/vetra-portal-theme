const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const projects = read('inc/projects.php');
const features = read('inc/site-features.php');
const customizer = read('inc/customizer.php');
const bootstrap = read('functions.php');
const guide = read('readme.md');
const layouts = read('inc/layouts.php');

test('stable project field identifiers are documented and exposed by API', () => {
  const ids = ['project_name', 'client_name', 'project_usage', 'contract_type', 'contract_number', 'contractor', 'contract_start', 'contract_duration', 'initial_amount', 'project_supervisor', 'project_manager', 'site_supervisor', 'project_address', 'client_address', 'urban_file_number', 'registry_sub', 'registry_main', 'category', 'summary', 'description'];
  for (const id of ids) {
    assert.ok(projects.includes("'" + id + "'") || projects.includes('"' + id + '"'));
    assert.ok(guide.includes('`' + id + '`'), `missing documentation for ${id}`);
  }
  assert.match(projects, /function vetra_project_get_field\(/);
  assert.match(projects, /function vetra_project_field_ids\(/);
});

test('project reads and writes are permission gated', () => {
  assert.ok(projects.includes("'approved' !== $project->status"));
  assert.ok(projects.includes("! current_user_can( 'vetra_create_projects' )"));
  assert.ok(projects.includes('! vetra_project_can_edit( $existing )'));
  assert.ok(projects.includes("'vetra_project_frontend'"));
  assert.ok(projects.includes("wp_die( 'شما مجاز به ویرایش این پروژه نیستید.'"));
});

test('registration forms do not expose approval status controls', () => {
  const renderer = projects.slice(projects.indexOf('function vetra_project_render_fields'), projects.indexOf('function vetra_project_admin_form_page'));
  assert.doesNotMatch(renderer, /name=["']status["']/);
  assert.match(projects, /\$data\['status'\] = 'pending';/);
});

test('page-role restrictions cover frontend, REST, and navigation output', () => {
  assert.match(features, /add_action\( 'template_redirect', 'vetra_enforce_page_access'/);
  assert.match(features, /add_filter\( 'rest_prepare_page', 'vetra_protect_rest_pages'/);
  assert.match(features, /add_filter\( 'wp_nav_menu_objects', 'vetra_filter_menu_by_role'/);
  assert.match(features, /add_shortcode\( 'vetra_restrict', 'vetra_restricted_shortcode'/);
});

test('WordPress menus and project moderation use explicit server-side capabilities', () => {
  assert.match(read('header.php'), /wp_nav_menu\( array\( 'theme_location' => 'primary'/);
  assert.ok(projects.includes("current_user_can( 'vetra_approve_projects' )"));
  assert.ok(projects.includes("check_admin_referer( 'vetra_project_moderate_' . $id )"));
  assert.ok(projects.includes("$data['status'] = $existing ? $existing->status : 'pending';"));
  assert.ok(projects.includes("'vetra_create_projects' => 'ایجاد پروژه'"));
});

test('role deletion is restricted to custom theme roles and administrator capability', () => {
  assert.ok(projects.includes("current_user_can( 'manage_options' )"));
  assert.ok(projects.includes("in_array( $slug, array( 'vetra_project_editor', 'vetra_project_manager' ), true )"));
  assert.ok(projects.includes("add_action( 'admin_post_vetra_role_delete', 'vetra_role_delete_action' )"));
});

test('sample pages have explicit rebuild/delete controls and stable markers', () => {
  assert.match(features, /meta_key' => '_vetra_sample_key'/);
  assert.match(features, /wp_update_post\( \$post, true \)/);
  assert.match(features, /wp_delete_post\( \$existing\[0\]->ID, true \)/);
  assert.match(features, /check_admin_referer\( 'vetra_sample_pages' \)/);
  assert.match(features, /add_action\( 'admin_post_vetra_sample_pages'/);
});

test('PWA controls are conditional and worker is served from site root', () => {
  assert.match(customizer, /'pwa_enabled'/);
  assert.match(customizer, /'pwa_install_prompt'/);
  assert.ok(customizer.includes("Service-Worker-Allowed: /"));
  assert.match(bootstrap, /home_url\( '\/vetra-sw' \)/);
  assert.match(bootstrap, /'installPrompt' =>/);
  assert.match(read('assets/js/corporate.js'), /!vetraPortal\.pwaEnabled[\s\S]*getRegistrations/);
});

test('maintenance, cookie notice, and company block patterns are wired', () => {
  assert.match(features, /status_header\( 503 \)/);
  assert.match(features, /current_user_can\( 'manage_options' \)/);
  assert.match(features, /get_permalink\( \$o\['policy_page'\] \)/);
  assert.match(features, /register_block_pattern\(/);
  assert.match(read('assets/js/corporate.js'), /vetra-cookie-accepted/);
});

test('theme page template and version declarations agree', () => {
  const version = bootstrap.match(/VETRA_PORTAL_VERSION', '([^']+)'/)[1];
  assert.equal(read('style.css').match(/^Version: (.+)$/m)[1], version);
  assert.match(bootstrap, /templates\/full-width\.php/);
  assert.match(read('templates/full-width.php'), /Template Name: وترا: تمام‌عرض/);
});

test('reusable header and footer layouts are registered and server-side selectable', () => {
  assert.match(bootstrap, /inc\/layouts\.php/);
  assert.match(layouts, /register_post_type\( 'vetra_layout'/);
  assert.match(layouts, /_vetra_header_layout/);
  assert.match(layouts, /_vetra_footer_layout/);
  assert.match(layouts, /vetra_render_layout\(/);
  assert.match(read('header.php'), /vetra_render_layout\( 'header' \)/);
  assert.match(read('footer.php'), /vetra_render_layout\( 'footer' \)/);
  assert.match(guide, /الگوهای هدر و فوتر/);
});

test('moderation and custom icon APIs are documented', () => {
  assert.match(projects, /function vetra_project_set_status\(/);
  assert.match(projects, /vetra_project_set_status\( \$id, \$status \)/);
  assert.match(projects, /vetra_approve_projects/);
  assert.match(read('inc/helpers.php'), /function vetra_custom_icon_url\(/);
  assert.match(guide, /vetra_custom_icon_url/);
});

test('complete theme architecture exposes shared schema and admin dashboard', () => {
  const schema = read('inc/core/settings-schema.php');
  const dashboard = read('inc/admin/class-dashboard.php');
  assert.match(bootstrap, /inc\/core\/settings-schema\.php/);
  assert.match(bootstrap, /class-dashboard\.php/);
  assert.match(schema, /function vetra_settings_schema\(/);
  assert.match(schema, /function vetra_settings_normalize\(/);
  assert.match(dashboard, /current_user_can\( 'manage_options' \)/);
  assert.match(dashboard, /wp_verify_nonce/);
  assert.match(dashboard, /vetra_admin_action/);
});

test('front-end hierarchy includes landing, distraction-free, search and reusable components', () => {
  assert.match(bootstrap, /templates\/distraction-free\.php/);
  assert.match(bootstrap, /templates\/landing\.php/);
  assert.ok(read('header.php').includes('vetra-search-overlay'));
  assert.ok(read('footer.php').includes('vetra-back-to-top'));
  assert.match(read('template-parts/content.php'), /vetra_component_related/);
  assert.match(read('assets/js/corporate.js'), /closeSearch/);
});

test('integration surface excludes removed providers and keeps supported adapters', () => {
  const integrations = read('inc/integrations.php');
  assert.doesNotMatch(integrations, /WPML|bbPress|WooCommerce|ICL_SITEPRESS/);
  assert.match(integrations, /class_exists\( 'GFForms' \)/);
  assert.match(integrations, /ELEMENTOR_VERSION/);
  assert.doesNotMatch(bootstrap, /enable_bbpress_style|is_bbpress/);
});
test('theme update metadata uses a stable slug and GitHub release checker', () => {
  assert.match(read('style.css'), /Theme Name: VETRA Portal/);
  assert.match(read('style.css'), /Text Domain: vetra-portal-theme/);
  assert.match(bootstrap, /class-updater\.php/);
  assert.match(read('inc/core/class-updater.php'), /THEME_SLUG = 'vetra-portal-theme'/);
  assert.match(read('inc/core/class-updater.php'), /api\.github\.com/);
});

test('admin settings use the collision-safe slug and no longer expose Customizer controls', () => {
  const dashboard = read('inc/admin/class-dashboard.php');
  const customizerFile = read('inc/customizer.php');
  assert.match(dashboard, /vetra-portal-settings/);
  assert.doesNotMatch(dashboard, /vetra-' \+ 'dashboard/);
  assert.match(customizerFile, /load-customize\.php/);
  assert.match(customizerFile, /vetra-portal-settings/);
  assert.match(bootstrap, /remove_action\( 'customize_register', 'vetra_layout_customizer_register'/);
});
test('Private Portal is capability-safe, opt-in, and represented in the dashboard', () => {
  const dashboard = read('inc/admin/class-dashboard.php');
  assert.match(bootstrap, /register_setting\( 'vetra_portal_options'/);
  assert.match(bootstrap, /template_redirect', 'vetra_private_portal_gate'/);
  assert.match(bootstrap, /current_user_can/);
  assert.match(dashboard, /vetra_private_portal/);
  assert.match(dashboard, /type=\"hidden\" name=\"vetra_private_portal\" value=\"0\"/);
  assert.match(read('assets/css/vetra-admin.css'), /vetra-toggle/);
});
test('updater normalizes GitHub extraction directories and prefers the release asset', () => {
  const updater = read('inc/core/class-updater.php');
  assert.match(updater, /upgrader_source_selection/);
  assert.match(updater, /fix_source_directory/);
	assert.match(updater, /'vetra\.zip'/);
	assert.match(updater, /in_array\( isset\( \$asset\['name'\]/);
	assert.match(read('.github/workflows/ci.yml'), /rsync -a/);
	assert.match(read('.github/workflows/ci.yml'), /vetra\.zip/);
	assert.match(read('.github/workflows/ci.yml'), /vetra-plus\.zip/);
});
