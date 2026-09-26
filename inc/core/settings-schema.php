<?php
/** Shared, versioned settings schema for Customizer and the admin panel. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function vetra_settings_schema() {
	static $schema;
	if ( null !== $schema ) { return $schema; }
	$schema = array(
		'header_enabled' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
		'footer_enabled' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
		'header_sticky' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => true, 'css' => '--vetra-header-sticky' ),
		'content_width' => array( 'type' => 'integer', 'default' => 1320, 'min' => 960, 'max' => 1800, 'sanitize' => 'absint', 'capability' => 'edit_theme_options', 'preview' => true, 'css' => '--vetra-content-width' ),
		'card_radius' => array( 'type' => 'integer', 'default' => 24, 'min' => 0, 'max' => 48, 'sanitize' => 'absint', 'capability' => 'edit_theme_options', 'preview' => true, 'css' => '--vetra-radius' ),
		'color_mode' => array( 'type' => 'enum', 'default' => 'system', 'choices' => array( 'light', 'dark', 'system' ), 'sanitize' => 'vetra_sanitize_mode', 'capability' => 'edit_theme_options', 'preview' => true, 'css' => null ),
		'mobile_menu_enabled' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
		'show_back_to_top' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
		'show_search' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
		'enable_forms_style' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
		'enable_bbpress_style' => array( 'type' => 'boolean', 'default' => true, 'sanitize' => 'vetra_sanitize_checkbox', 'capability' => 'edit_theme_options', 'preview' => false, 'css' => null ),
	);
	return $schema;
}

function vetra_settings_schema_version() { return 1; }
function vetra_settings_defaults() { $out = array(); foreach ( vetra_settings_schema() as $key => $field ) { $out[ $key ] = $field['default']; } return $out; }
function vetra_settings_normalize( $values ) {
	$values = is_array( $values ) ? $values : array(); $out = vetra_settings_defaults();
	foreach ( vetra_settings_schema() as $key => $field ) {
		if ( ! array_key_exists( $key, $values ) ) { continue; }
		$value = $values[ $key ];
		if ( 'boolean' === $field['type'] ) { $out[ $key ] = ! empty( $value ); }
		elseif ( 'integer' === $field['type'] ) { $out[ $key ] = max( $field['min'], min( $field['max'], absint( $value ) ) ); }
		elseif ( 'enum' === $field['type'] ) { $out[ $key ] = in_array( $value, $field['choices'], true ) ? $value : $field['default']; }
	}
	return $out;
}
function vetra_operational_settings() { return vetra_settings_normalize( get_option( 'vetra_operational_settings', array() ) ); }
function vetra_clear_dynamic_css_cache() { update_option( 'vetra_css_cache_version', time(), false ); delete_transient( 'vetra_dynamic_css_' . md5( wp_json_encode( vetra_theme_css_fingerprint() ) ) ); }
function vetra_theme_css_fingerprint() { return array( 'version' => defined( 'VETRA_PORTAL_VERSION' ) ? VETRA_PORTAL_VERSION : '0', 'schema' => vetra_settings_schema_version(), 'cache' => absint( get_option( 'vetra_css_cache_version', 0 ) ), 'mods' => get_theme_mods() ); }
add_action( 'customize_save_after', 'vetra_clear_dynamic_css_cache' );
