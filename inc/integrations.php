<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function vetra_integration_is_active( $name ) {
	$checks = array( 'wpml' => defined( 'ICL_SITEPRESS_VERSION' ), 'bbpress' => function_exists( 'is_bbpress' ), 'gravityforms' => class_exists( 'GFForms' ), 'woocommerce' => class_exists( 'WooCommerce' ) );
	return ! empty( $checks[ sanitize_key( $name ) ] );
}
function vetra_integration_enqueue_forms() {
	if ( ! vetra_option( 'enable_forms_style', true ) ) { return; }
	if ( is_page() && ( function_exists( 'gravity_form' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'contact-form-7' ) ) ) { wp_enqueue_style( 'vetra-forms', VETRA_PORTAL_URI . '/assets/css/forms.css', array( 'vetra-corporate' ), VETRA_PORTAL_VERSION ); }
}
add_action( 'wp_enqueue_scripts', 'vetra_integration_enqueue_forms', 30 );
function vetra_integration_wpml_language_switcher() { if ( ! vetra_integration_is_active( 'wpml' ) || ! function_exists( 'icl_get_languages' ) ) { return; } $languages = icl_get_languages( 'skip_missing=0' ); if ( ! empty( $languages ) ) { echo '<nav class="vetra-language-switcher" aria-label="' . esc_attr__( 'انتخاب زبان', 'vetra-portal' ) . '">'; foreach ( $languages as $language ) { echo '<a href="' . esc_url( $language['url'] ) . '" lang="' . esc_attr( $language['language_code'] ) . '"' . ( $language['active'] ? ' aria-current="page"' : '' ) . '>' . esc_html( $language['native_name'] ) . '</a>'; } echo '</nav>'; } }
