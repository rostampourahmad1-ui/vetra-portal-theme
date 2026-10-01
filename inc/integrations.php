<?php
/** Optional form integration kept intentionally small and dependency-safe. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function vetra_integration_is_active( $name ) {
	$checks = array( 'gravityforms' => class_exists( 'GFForms' ), 'elementor' => defined( 'ELEMENTOR_VERSION' ) );
	return ! empty( $checks[ sanitize_key( $name ) ] );
}
function vetra_integration_enqueue_forms() {
	if ( ! vetra_option( 'enable_forms_style', true ) ) { return; }
	if ( is_page() && ( function_exists( 'gravity_form' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'contact-form-7' ) ) ) {
		wp_enqueue_style( 'vetra-forms', VETRA_PORTAL_URI . '/assets/css/forms.css', array( 'vetra-corporate' ), VETRA_PORTAL_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'vetra_integration_enqueue_forms', 30 );
