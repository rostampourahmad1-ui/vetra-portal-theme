<?php
/**
 * VETRA Icon Pack — library.
 *
 * Single source of truth is `assets/icons/vetra-icons.json`. This file loads,
 * caches and validates it, and exposes lookup + sprite helpers. It performs no
 * output on its own and is safe to load in front-end, admin, REST and WP-CLI
 * contexts.
 *
 * Security model:
 *   - Icon names are whitelisted against the JSON library (and its aliases).
 *   - Geometry is trusted, local, version-controlled data; it is still filtered
 *     through `vetra_icon_validate_markup()` as defence in depth.
 *   - No user-supplied string is ever written into an SVG attribute or element.
 *
 * @package VetraPortal
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VETRA_ICONS_VERSION', '1.0.0' );
define( 'VETRA_ICONS_DIR', VETRA_PORTAL_DIR . '/assets/icons' );
define( 'VETRA_ICONS_URI', VETRA_PORTAL_URI . '/assets/icons' );

/**
 * Load and cache the icon library.
 *
 * @return array{version:string,viewBox:string,strokeWidth:float,icons:array,aliases:array}
 */
function vetra_icon_library() {
	static $library = null;

	if ( null !== $library ) {
		return $library;
	}

	$fallback = array(
		'version'     => VETRA_ICONS_VERSION,
		'viewBox'     => '0 0 24 24',
		'strokeWidth' => 1.5,
		'icons'       => array(),
		'aliases'     => array(),
	);

	$file = VETRA_ICONS_DIR . '/vetra-icons.json';
	if ( ! is_readable( $file ) ) {
		$library = $fallback;
		return $library;
	}

	$raw     = file_get_contents( $file );
	$decoded = json_decode( $raw, true );

	if ( ! is_array( $decoded ) || ! isset( $decoded['icons'] ) || ! is_array( $decoded['icons'] ) ) {
		$library = $fallback;
		return $library;
	}

	$decoded['version']     = isset( $decoded['version'] ) ? (string) $decoded['version'] : VETRA_ICONS_VERSION;
	$decoded['viewBox']     = isset( $decoded['viewBox'] ) ? (string) $decoded['viewBox'] : '0 0 24 24';
	$decoded['strokeWidth'] = isset( $decoded['strokeWidth'] ) ? (float) $decoded['strokeWidth'] : 1.5;
	$decoded['aliases']     = isset( $decoded['aliases'] ) && is_array( $decoded['aliases'] ) ? $decoded['aliases'] : array();

	$library = $decoded;

	return $library;
}

/**
 * Resolve a name (or alias) to its canonical key.
 *
 * @param string $name Raw icon name.
 * @return string Empty string when unknown.
 */
function vetra_icon_resolve_name( $name ) {
	$name = is_string( $name ) ? strtolower( trim( $name ) ) : '';
	if ( '' === $name ) {
		return '';
	}

	$library = vetra_icon_library();
	$aliases = $library['aliases'];

	if ( isset( $aliases[ $name ] ) ) {
		$name = strtolower( (string) $aliases[ $name ] );
	}

	return isset( $library['icons'][ $name ] ) ? $name : '';
}

/**
 * Whether an icon (or alias) exists in the pack.
 *
 * @param string $name Icon name.
 * @return bool
 */
function vetra_icon_exists( $name ) {
	return '' !== vetra_icon_resolve_name( $name );
}

/**
 * Return a single icon definition.
 *
 * @param string $name Icon name or alias.
 * @return array|null
 */
function vetra_icon_get( $name ) {
	$key = vetra_icon_resolve_name( $name );
	if ( '' === $key ) {
		return null;
	}
	$library = vetra_icon_library();
	$icon    = $library['icons'][ $key ];
	$icon['name'] = $key;

	return $icon;
}

/**
 * All canonical icon names (aliases excluded).
 *
 * @return string[]
 */
function vetra_icon_names() {
	return array_keys( vetra_icon_library()['icons'] );
}

/**
 * All alias => canonical pairs.
 *
 * @return array<string,string>
 */
function vetra_icon_aliases() {
	return vetra_icon_library()['aliases'];
}

/**
 * Validate a single inline SVG element string.
 *
 * Allows only the geometry tags used by the pack and rejects any markup that
 * could execute code or load external resources.
 *
 * @param string $markup Candidate element, e.g. `<path d='...'/>`.
 * @return bool
 */
function vetra_icon_validate_markup( $markup ) {
	if ( ! is_string( $markup ) || '' === $markup ) {
		return false;
	}

	$allowed_tags = array( 'path', 'circle', 'rect', 'line', 'polyline', 'polygon', 'g' );
	if ( ! preg_match( '/^<([a-zA-Z]+)(\s[^>]*)?\/?>$/', trim( $markup ), $matches ) ) {
		return false;
	}
	if ( ! in_array( strtolower( $matches[1] ), $allowed_tags, true ) ) {
		return false;
	}

	$blocked = array( 'script', 'style', 'foreignobject', 'iframe', 'image', 'use', 'animate', 'set', 'onload', 'onerror', 'onclick', 'javascript:', 'data:', 'href=', 'xlink:', 'url(' );
	$lower   = strtolower( $markup );
	foreach ( $blocked as $needle ) {
		if ( false !== strpos( $lower, $needle ) ) {
			return false;
		}
	}

	// Reject any event-handler attribute (on*="...").
	if ( preg_match( '/\son[a-z]+\s*=/i', $markup ) ) {
		return false;
	}

	return true;
}

/**
 * Sanitise a CSS colour value. Only hex and rgb/rgba are accepted.
 *
 * @param string $color Candidate colour.
 * @return string Empty string when invalid.
 */
function vetra_icon_sanitize_color( $color ) {
	$color = is_string( $color ) ? trim( $color ) : '';
	if ( '' === $color ) {
		return '';
	}
	if ( preg_match( '/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $color ) ) {
		return $color;
	}
	if ( preg_match( '/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(\s*,\s*(0|1|0?\.\d+))?\s*\)$/', $color ) ) {
		return $color;
	}

	return '';
}

/**
 * Render one element, or an empty string when invalid.
 *
 * @param string $markup Validated-or-not element markup.
 * @return string
 */
function vetra_icon_render_element( $markup ) {
	return vetra_icon_validate_markup( $markup ) ? $markup : '';
}

/**
 * Build a full sprite of `<symbol>` elements from the library.
 *
 * Useful for a single hidden sprite printed once in the footer; consumers then
 * reference icons with `<use href="#vetra-icon-name">`.
 *
 * @return string Escaped, self-contained SVG markup.
 */
function vetra_icon_sprite_markup() {
	$library = vetra_icon_library();
	$out     = '<svg xmlns="http://www.w3.org/2000/svg" class="vetra-icon-sprite" aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">';

	foreach ( $library['icons'] as $name => $icon ) {
		$name = sanitize_key( $name );
		if ( '' === $name ) {
			continue;
		}
		$out .= '<symbol id="vetra-icon-' . esc_attr( $name ) . '" viewBox="' . esc_attr( $library['viewBox'] ) . '">';
		$out .= '<g class="vetra-icon-base">' . vetra_icon_inner( $icon, 'base' ) . '</g>';
		$out .= '<g class="vetra-icon-accent">' . vetra_icon_inner( $icon, 'accent' ) . '</g>';
		$out .= '</symbol>';
	}

	$out .= '</svg>';

	return $out;
}

/**
 * Concatenate validated elements of a given layer for one icon.
 *
 * @param array  $icon  Icon definition.
 * @param string $layer base|accent.
 * @return string
 */
function vetra_icon_inner( $icon, $layer ) {
	if ( empty( $icon[ $layer ] ) || ! is_array( $icon[ $layer ] ) ) {
		return '';
	}

	$html = '';
	foreach ( $icon[ $layer ] as $element ) {
		$html .= vetra_icon_render_element( $element );
	}

	return $html;
}
