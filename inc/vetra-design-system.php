<?php
/**
 * VETRA Design System loader.
 *
 * Shared, namespaced design layer for the four VETRA products:
 *   1. vetra-portal-theme
 *   2. Vetra RebarCut
 *   3. Vetra Dashboard
 *   4. VETRA GANTT
 *
 * The assets (`assets/css/vetra-*.css`, `assets/js/vetra-ui.js`) are plain,
 * framework-free files. Other products may consume them directly or call
 * `vetra_ds_enqueue_assets()` when the theme is active.
 *
 * @package VetraPortal
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Guard against double-loading when another VETRA product includes this file
// alongside the active theme.
if ( defined( 'VETRA_DS_VERSION' ) ) {
	return;
}

define( 'VETRA_DS_VERSION', '1.0.0' );
define( 'VETRA_DS_DIR', VETRA_PORTAL_DIR . '/assets' );
define( 'VETRA_DS_URI', VETRA_PORTAL_URI . '/assets' );
define( 'VETRA_DS_HANDLE', 'vetra-design-system' );

/**
 * Enqueue the design-system assets in dependency order.
 *
 * Safe to call multiple times and from other VETRA products/plugins. Nothing is
 * executed when WordPress is unavailable, and no third-party plugin is required.
 *
 * @return void
 */
function vetra_ds_enqueue_assets() {
	$ver  = VETRA_DS_VERSION;
	$base = VETRA_DS_URI;

	wp_enqueue_style( VETRA_DS_HANDLE . '-tokens', $base . '/css/vetra-tokens.css', array(), $ver );
	wp_enqueue_style( VETRA_DS_HANDLE . '-components', $base . '/css/vetra-components.css', array( VETRA_DS_HANDLE . '-tokens' ), $ver );
	wp_enqueue_style( VETRA_DS_HANDLE . '-utilities', $base . '/css/vetra-utilities.css', array( VETRA_DS_HANDLE . '-components' ), $ver );
	wp_enqueue_style( VETRA_DS_HANDLE . '-print', $base . '/css/vetra-print.css', array( VETRA_DS_HANDLE . '-components' ), $ver, 'print' );

	wp_enqueue_script( VETRA_DS_HANDLE, $base . '/js/vetra-ui.js', array(), $ver, true );
	wp_script_add_data( VETRA_DS_HANDLE, 'strategy', 'defer' );

	wp_localize_script(
		VETRA_DS_HANDLE,
		'vetraDS',
		array(
			'themeMode' => vetra_ds_default_theme_mode(),
			'labels'    => array(
				'notifications' => __( 'اعلان‌ها', 'vetra-portal-theme' ),
				'dismiss'       => __( 'بستن اعلان', 'vetra-portal-theme' ),
				'toggleTheme'   => __( 'تغییر حالت رنگی', 'vetra-portal-theme' ),
			),
		)
	);

	/**
	 * Fires once the design-system assets are registered.
	 *
	 * @param string $handle Base style/script handle.
	 */
	do_action( 'vetra_ds_assets_enqueued', VETRA_DS_HANDLE );
}

/**
 * Resolve the default colour mode from theme settings, falling back to auto.
 *
 * @return string One of: light, dark, auto.
 */
function vetra_ds_default_theme_mode() {
	$mode = function_exists( 'vetra_option' ) ? vetra_option( 'color_mode', 'auto' ) : 'auto';
	$mode = is_string( $mode ) ? strtolower( $mode ) : 'auto';

	return in_array( $mode, array( 'light', 'dark', 'auto' ), true ) ? $mode : 'auto';
}

add_action( 'wp_enqueue_scripts', 'vetra_ds_enqueue_assets', 20 );

/* ---------------------------------------------------------------------- *
 * Server-side component helpers — every value is sanitised on input and
 * escaped on output. No privileged/AJAX operation is performed here, so no
 * nonce is required; any future state-changing endpoint must add its own
 * capability + nonce check.
 * ---------------------------------------------------------------------- */

/**
 * Build a safe class attribute value from a base class and modifiers.
 *
 * @param string        $base      Base class.
 * @param string|array  $modifiers Modifier suffixes or extra classes.
 * @return string Escaped, space-separated class list.
 */
function vetra_ds_class( $base, $modifiers = array() ) {
	$classes = array( sanitize_html_class( $base ) );
	foreach ( (array) $modifiers as $modifier ) {
		$modifier = trim( (string) $modifier );
		if ( '' === $modifier ) {
			continue;
		}
		if ( 0 === strpos( $modifier, 'vetra-' ) ) {
			$classes[] = sanitize_html_class( $modifier );
		} else {
			$classes[] = sanitize_html_class( $base . '--' . $modifier );
		}
	}

	return esc_attr( implode( ' ', array_unique( array_filter( $classes ) ) ) );
}

/**
 * Render a namespaced button.
 *
 * @param array $args {
 *     @type string $label     Visible label (required).
 *     @type string $variant   primary|secondary|ghost|danger. Default primary.
 *     @type string $size      sm|md|lg. Default md.
 *     @type string $href      Render an anchor when provided.
 *     @type string $type      Button type attribute. Default button.
 *     @type string $class     Extra classes.
 *     @type array  $attrs     Extra HTML attributes (name => value); escaped.
 *     @type bool   $disabled  Disabled state.
 * }
 * @return string Escaped HTML.
 */
function vetra_ds_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'label'    => '',
			'variant'  => 'primary',
			'size'     => '',
			'href'     => '',
			'type'     => 'button',
			'class'    => '',
			'attrs'    => array(),
			'disabled' => false,
		)
	);

	$variants = array( 'primary', 'secondary', 'ghost', 'danger' );
	$sizes    = array( 'sm', 'md', 'lg' );
	$variant  = in_array( $args['variant'], $variants, true ) ? $args['variant'] : 'primary';
	$size     = in_array( $args['size'], $sizes, true ) ? $args['size'] : '';

	$classes = 'vetra-btn vetra-btn--' . $variant;
	if ( $size ) {
		$classes .= ' vetra-btn--' . $size;
	}
	if ( $args['class'] ) {
		$classes .= ' ' . sanitize_html_class( $args['class'] );
	}

	$attributes = array( 'class' => esc_attr( $classes ) );
	foreach ( (array) $args['attrs'] as $name => $value ) {
		$name = preg_replace( '/[^a-zA-Z0-9_\-:]/', '', (string) $name );
		if ( '' === $name || 0 === strpos( strtolower( $name ), 'on' ) ) {
			continue; // Never allow inline event handlers.
		}
		$attributes[ $name ] = esc_attr( (string) $value );
	}

	$attribute_html = '';
	foreach ( $attributes as $name => $value ) {
		$attribute_html .= ' ' . $name . '="' . $value . '"';
	}

	if ( $args['disabled'] ) {
		$attribute_html .= ' disabled aria-disabled="true"';
	}

	if ( $args['href'] ) {
		return '<a href="' . esc_url( $args['href'] ) . '"' . $attribute_html . '>' . esc_html( $args['label'] ) . '</a>';
	}

	$type = in_array( $args['type'], array( 'button', 'submit', 'reset' ), true ) ? $args['type'] : 'button';

	return '<button type="' . esc_attr( $type ) . '"' . $attribute_html . '>' . esc_html( $args['label'] ) . '</button>';
}

/**
 * Render a status badge.
 *
 * Status is never communicated by colour alone: a visible label is required and
 * the modifier is limited to the approved semantic set.
 *
 * @param string $label  Badge text (required).
 * @param string $status neutral|info|success|warning|critical.
 * @return string Escaped HTML.
 */
function vetra_ds_badge( $label, $status = 'neutral' ) {
	$allowed = array( 'neutral', 'info', 'success', 'warning', 'critical' );
	$status  = in_array( $status, $allowed, true ) ? $status : 'neutral';

	return '<span class="' . vetra_ds_class( 'vetra-badge', array( $status ) ) . '">' . esc_html( $label ) . '</span>';
}

/**
 * Render an alert.
 *
 * @param string $message Body text (required).
 * @param string $type    info|success|warning|critical.
 * @param string $title   Optional heading.
 * @return string Escaped HTML.
 */
function vetra_ds_alert( $message, $type = 'info', $title = '' ) {
	$allowed = array( 'info', 'success', 'warning', 'critical' );
	$type    = in_array( $type, $allowed, true ) ? $type : 'info';

	$html  = '<div class="' . vetra_ds_class( 'vetra-alert', array( $type ) ) . '" role="alert">';
	if ( $title ) {
		$html .= '<p class="vetra-alert__title">' . esc_html( $title ) . '</p>';
	}
	$html .= '<div class="vetra-alert__body">' . esc_html( $message ) . '</div></div>';

	return $html;
}

/**
 * Return the allowed semantic status values for integration code.
 *
 * @return array
 */
function vetra_ds_status_values() {
	return array( 'neutral', 'info', 'success', 'warning', 'critical' );
}

/**
 * Register an opt-in preview page template so teams can visually verify Light,
 * Dark, RTL, responsive and accessibility states before shipping.
 *
 * @param array $templates Existing page templates.
 * @return array
 */
function vetra_ds_register_preview_template( $templates ) {
	$templates['templates/design-system.php'] = __( 'وترا: پیش‌نمایش دیزاین‌سیستم', 'vetra-portal-theme' );

	return $templates;
}
add_filter( 'theme_page_templates', 'vetra_ds_register_preview_template' );

/**
 * Resolve the design-system preview template when assigned to a page.
 *
 * @param string $template Current template path.
 * @return string
 */
function vetra_ds_load_preview_template( $template ) {
	if ( is_page() && 'templates/design-system.php' === get_page_template_slug() ) {
		$custom = VETRA_DS_DIR . '/../templates/design-system.php';
		if ( is_readable( $custom ) ) {
			return $custom;
		}
	}

	return $template;
}
add_filter( 'template_include', 'vetra_ds_load_preview_template' );
