<?php
/**
 * Corporate theme helpers.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_brand_logo_url( $variant = '' ) {
	$variant_id = $variant ? absint( get_theme_mod( 'vetra_logo_' . $variant, 0 ) ) : 0;
	$logo_id = $variant_id ? $variant_id : absint( get_theme_mod( 'vetra_logo', 0 ) );
	if ( $logo_id ) {
		return wp_get_attachment_image_url( $logo_id, 'full' );
	}
	return VETRA_PORTAL_URI . '/assets/images/logo.png';
}

function vetra_image_url( $key ) {
	$id = absint( get_theme_mod( 'vetra_' . $key, 0 ) );
	return $id ? wp_get_attachment_image_url( $id, 'full' ) : '';
}

/** Return the URL of the centrally managed custom icon, if configured. */
function vetra_custom_icon_url() {
	return vetra_image_url( 'custom_icon' );
}

/**
 * Icon output lives in the VETRA Icon Pack.
 *
 * @see inc/vetra-icons.php  vetra_icon() / vetra_inline_icon()
 */

function vetra_image_or_class( $key, $class = '' ) {
	$image = vetra_image_url( $key );
	return $image ? '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $image ) . '" alt="" loading="lazy" />' : '';
}
