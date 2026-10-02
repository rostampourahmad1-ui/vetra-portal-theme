const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const schema = read('vetra-plus/modules/settings/class-settings-schema.php');
const migration = read('vetra-plus/modules/settings/class-migration.php');
const settings = read('vetra-plus/modules/settings/class-settings.php');
const loader = read('vetra-plus/includes/class-loader.php');
const plugin = read('vetra-plus/includes/class-plugin.php');

test('phase 6 settings schema covers all requested categories with vetra-prefixed keys', () => {
  for (const category of ['identity', 'typography', 'header', 'footer', 'custom']) {
    assert.match(schema, new RegExp(`'${category}'`));
  }
  assert.match(schema, /vetra_primary_color/);
  assert.match(schema, /vetra_font_family/);
  assert.match(schema, /vetra_header_enabled/);
  assert.match(schema, /vetra_footer_enabled/);
  assert.doesNotMatch(schema, /vetra_projects_/);
  assert.match(schema, /vetra_custom_css/);
});

test('settings panel uses capability, nonce, Settings API, export, import and reset', () => {
  assert.match(settings, /register_setting/);
  assert.match(settings, /manage_options/);
  assert.match(settings, /check_admin_referer/);
  assert.match(settings, /wp_nonce_url/);
  assert.match(settings, /admin_post_vetra_plus_settings_export/);
  assert.match(settings, /admin_post_vetra_plus_settings_import/);
  assert.match(settings, /admin_post_vetra_plus_settings_reset/);
  assert.match(settings, /vetra_plus_settings/);
});

test('migration is JSON-only, deterministic, allow-listed and does not execute serialized code', () => {
  assert.match(migration, /json_decode/);
  assert.match(migration, /map_key/);
  assert.match(migration, /vetra_/);
  assert.match(migration, /SettingsSchema::flat_fields/);
  assert.match(migration, /sanitize_value/);
  assert.doesNotMatch(migration, /eval\s*\(/);
  assert.doesNotMatch(migration, /unserialize\s*\(/);
  assert.match(migration, /vetra_plus_backup_missing/);
  assert.match(migration, /vetra_plus_backup_no_supported_keys/);
});

test('phase 6 services are loaded independently by Vetra Plus', () => {
  assert.match(loader, /SettingsSchema/);
  assert.match(loader, /Migration/);
  assert.match(loader, /Settings/);
  assert.match(plugin, /Settings\\Settings/);
  assert.doesNotMatch([schema, migration, settings].join('\n'), /WooCommerce|WPBakery|Codevz/i);
});
