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

function vetra_inline_icon( $name = 'arrow' ) {
	$icons = array(
		'arrow'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13M13 6l6 6-6 6"/></svg>',
		'arrow-up' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 17 10-10M8 7h9v9"/></svg>',
		'building' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 20.5V7l7-3.5 7 3.5v13.5M9 20.5v-4h6v4M8 10h.01M12 10h.01M16 10h.01M8 13h.01M12 13h.01M16 13h.01"/></svg>',
		'leaf'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.5 4.5C11 4.7 5.7 8.2 5.7 14.2c0 2.9 2.1 5 5 5 6 0 8.6-5.5 8.8-14.7ZM4 20c2.7-4.7 6.2-7.6 10.8-9.5"/></svg>',
		'pen'      => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 16-.8 4 4-.8L19 8.4a2.4 2.4 0 0 0-3.4-3.4L5 16Zm8-8 3 3M5 20h15"/></svg>',
		'cube'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Zm-8 4.5 8 4.7 8-4.7M12 12.2V21"/></svg>',
		'people'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.2"/><path d="M3.5 19c.7-3 2.5-4.5 5.5-4.5s4.8 1.5 5.5 4.5M15 14.5c2.8-.1 4.5 1.3 5 3.8"/></svg>',
		'chart'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19.5V11m7 8.5V5m7 14.5v-6M3.5 19.5h17"/></svg>',
		'moon'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.2 15.5A8.5 8.5 0 0 1 8.5 4.8 8.5 8.5 0 1 0 19.2 15.5Z"/></svg>',
		'sun'      => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3.5"/><path d="M12 2.5v2M12 19.5v2M4.6 4.6 6 6M18 18l1.4 1.4M2.5 12h2M19.5 12h2M4.6 19.4 6 18M18 6l1.4-1.4"/></svg>',
		'menu'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>',
		'check'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 5 5L20 7"/></svg>',
		'file'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['arrow'];
}

function vetra_image_or_class( $key, $class = '' ) {
	$image = vetra_image_url( $key );
	return $image ? '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $image ) . '" alt="" loading="lazy" />' : '';
}
