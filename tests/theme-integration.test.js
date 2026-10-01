const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');

const projects = read('inc/projects.php');
const front = read('front-page.php');
const customizer = read('inc/customizer.php');
const schema = read('inc/core/settings-schema.php');
const corporateJs = read('assets/js/corporate.js');
const formsCss = read('assets/css/forms.css');
const functionsPhp = read('functions.php');

const OFFICIAL = ['#C67D34', '#12161A', '#F6F7F9', '#8A95A5', '#222933'];

test('theme version is bumped consistently to 3.9.0', () => {
  assert.match(functionsPhp, /VETRA_PORTAL_VERSION', '3\.9\.0'/);
  assert.equal(read('style.css').match(/^Version: (.+)$/m)[1], '3.9.0');
  assert.equal(JSON.parse(read('package.json')).version, '3.9.0');
});

test('official VETRA palette is the default and legacy accent is removed', () => {
  for (const hex of OFFICIAL) {
    assert.ok(customizer.toUpperCase().includes(hex), `customizer missing ${hex}`);
  }
  assert.equal((customizer.match(/#f28b38/gi) || []).length, 0, 'legacy #f28b38 still present');
  assert.equal((customizer.match(/#ffad57/gi) || []).length, 0, 'legacy #ffad57 still present');
  assert.equal((customizer.match(/#F97316/gi) || []).length, 0, 'forbidden #F97316 present');
});

test('card radius default is capped at 8px', () => {
  assert.match(customizer, /'card_radius'\s*=>\s*8/);
  assert.match(schema, /'card_radius' => array\( 'type' => 'integer', 'default' => 8, 'min' => 0, 'max' => 8/);
});

test('featured projects read from the table with a safe, gated query', () => {
  assert.match(projects, /function vetra_featured_projects_get\(/);
  assert.match(projects, /SHOW TABLES LIKE/);
  assert.match(projects, /current_user_can\( 'vetra_manage_projects' \)/);
  assert.match(projects, /current_user_can\( 'vetra_edit_own_projects' \)/);
  assert.match(projects, /status = 'approved'/);
  assert.match(projects, /ORDER BY updated_at DESC LIMIT %d/);
  // Only required columns are selected (no SELECT *).
  assert.doesNotMatch(projects, /SELECT \* FROM \{?\{?\$table/);
});

test('featured project cards escape every output and provide a status badge', () => {
  assert.match(projects, /function vetra_featured_project_card\(/);
  assert.match(projects, /esc_html\( \$project->project_name \)/);
  assert.match(projects, /esc_url\( \$detail_url \)/);
  assert.match(projects, /absint\( \$project->id \)/);
  assert.match(projects, /wp_get_attachment_image\(/);
  assert.match(projects, /vetra_project_status_meta\(/);
  assert.match(projects, /function vetra_featured_projects_fallback\(/);
});

test('front page prefers the database and falls back to Customizer then empty state', () => {
  assert.match(front, /vetra_featured_projects_get\(/);
  assert.match(front, /vetra_featured_project_card\(/);
  assert.match(front, /vetra_featured_projects_fallback\(/);
  assert.match(front, /vetra-empty/);
});

test('dark/light manual toggle is persisted in localStorage', () => {
  assert.match(corporateJs, /localStorage/);
  assert.match(corporateJs, /vetra-theme/);
  assert.match(corporateJs, /prefers-color-scheme/);
});

test('Gravity Forms focus, disabled, submitting and success states are styled', () => {
  assert.match(formsCss, /:focus/);
  assert.match(formsCss, /gform_button:disabled/);
  assert.match(formsCss, /aria-busy="true"/);
  assert.match(formsCss, /gform_confirmation_message/);
  assert.match(formsCss, /vetra-color-success/);
});

test('mobile bottom bar uses VETRA icon pack', () => {
  assert.match(functionsPhp, /vetra_bar_icons/);
  assert.match(functionsPhp, /vetra_icon\( \$vetra_bar_icon/);
  assert.match(functionsPhp, /folder-project/);
});
