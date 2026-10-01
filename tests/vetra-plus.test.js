const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (name) => fs.readFileSync(path.join(root, name), 'utf8');
const plugin = read('vetra-plus/vetra-plus.php');
const lifecycle = read('vetra-plus/includes/class-plugin.php');
const postTypes = read('vetra-plus/includes/class-post-types.php');
const meta = read('vetra-plus/includes/class-meta-boxes.php');
const api = read('vetra-plus/includes/class-api.php');
const capabilities = read('vetra-plus/includes/class-capabilities.php');

test('Vetra Plus is an independent PHP 8.2 plugin with guarded lifecycle hooks', () => {
  assert.match(plugin, /Plugin Name: Vetra Plus/);
  assert.match(plugin, /Requires PHP: 8\.2/);
  assert.match(plugin, /defined\( 'ABSPATH' \)/);
  assert.match(lifecycle, /register_activation_hook/);
  assert.match(lifecycle, /register_deactivation_hook/);
  assert.match(plugin, /class-loader\.php/);
});

test('Vetra Plus registers the project CPT and dedicated taxonomies', () => {
  assert.match(postTypes, /register_post_type\(/);
  assert.match(postTypes, /'vetra_project'/);
  for (const taxonomy of ['vetra_project_category', 'vetra_project_skill', 'vetra_project_feature']) {
    assert.match(postTypes, new RegExp(taxonomy));
  }
  assert.match(postTypes, /show_in_rest' => true/);
  assert.match(postTypes, /map_meta_cap' => true/);
});

test('project metadata uses native nonce, capability, sanitize and allow-list controls', () => {
  assert.match(meta, /wp_nonce_field/);
  assert.match(meta, /wp_verify_nonce/);
  assert.match(meta, /current_user_can\( 'edit_post'/);
  assert.match(meta, /sanitize_text_field/);
  assert.match(meta, /in_array\( \$status, array\( 'planned', 'active', 'completed', 'archived' \)/);
  assert.match(meta, /register_post_meta/);
});

test('company project capabilities and public REST API are explicitly defined', () => {
  assert.match(capabilities, /vetra_project_editor/);
  assert.match(capabilities, /vetra_project_manager/);
  assert.match(capabilities, /manage_vetra_project_terms/);
  assert.match(api, /register_rest_route\( 'vetra\/v1', '\/projects'/);
  assert.match(api, /'post_status' => 'publish'/);
  assert.match(api, /vetra_plus_project_data/);
});

test('Vetra Plus does not introduce forbidden builders or commerce coupling', () => {
  const source = [plugin, postTypes, meta, api, capabilities, read('vetra-plus/includes/class-plugin.php')].join('\n');
  assert.doesNotMatch(source, /WooCommerce|WPBakery|Codevz|wc_/i);
});
