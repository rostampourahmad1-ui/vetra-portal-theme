<?php
/**
 * VETRA Icon Pack — renderer and public API.
 *
 * Exposes `vetra_icon( $name, $args )`, the `[vetra_icon]` shortcode, the
 * Dashicons-free admin menu helper and a backward-compatible
 * `vetra_inline_icon()` wrapper. All output is escaped; unknown names fall back
 * to a known icon instead of breaking.
 *
 * @package VetraPortal
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Fallback icon rendered when a name is unknown. */
if ( ! defined( 'VETRA_ICON_FALLBACK' ) ) {
	define( 'VETRA_ICON_FALLBACK', 'info' );
}

/**
 * Render an inline SVG icon.
 *
 * @param string $name Icon name or alias.
 * @param array  $args {
 *     @type string $class        Extra CSS classes.
 *     @type string|int $size     Any CSS length (24, "24", "1.5em"). Default 24.
 *     @type string $label        Accessible name for functional icons.
 *     @type string $title        Accessible name / tooltip text.
 *     @type string $base_color   Override base stroke colour (hex or rgb).
 *     @type string $accent_color Override accent stroke colour (hex or rgb).
 *     @type bool   $decorative   Force decorative (aria-hidden).
 *     @type bool   $aria_hidden  Force aria-hidden.
 *     @type bool   $rtl_flip     Flip directional icons in RTL. Default true.
 *     @type float  $stroke_width Override stroke width.
 * }
 * @return string Escaped SVG markup.
 */
function vetra_icon( $name, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'        => '',
			'size'         => 24,
			'label'        => '',
			'title'        => '',
			'base_color'   => '',
			'accent_color' => '',
			'decorative'   => null,
			'aria_hidden'  => null,
			'rtl_flip'     => true,
			'stroke_width' => null,
		)
	);

	$key  = vetra_icon_resolve_name( $name );
	$icon = '' !== $key ? vetra_icon_get( $key ) : null;

	// Unknown name: return a valid, known fallback instead of broken markup.
	if ( null === $icon ) {
		$key  = vetra_icon_resolve_name( VETRA_ICON_FALLBACK );
		$icon = '' !== $key ? vetra_icon_get( $key ) : null;
		if ( null === $icon ) {
			return '';
		}
	}

	$library = vetra_icon_library();

	$label = is_string( $args['label'] ) ? sanitize_text_field( $args['label'] ) : '';
	$title = is_string( $args['title'] ) ? sanitize_text_field( $args['title'] ) : '';

	$decorative = is_bool( $args['decorative'] )
		? $args['decorative']
		: ( '' === $label && '' === $title );
	$aria_hidden = is_bool( $args['aria_hidden'] ) ? $args['aria_hidden'] : $decorative;

	$size = vetra_icon_sanitize_size( $args['size'] );

	$classes = array( 'vetra-icon', 'vetra-icon--' . sanitize_html_class( $key ) );
	if ( ! empty( $icon['directional'] ) && ! empty( $args['rtl_flip'] ) ) {
		$classes[] = 'vetra-icon--directional';
	}
	if ( is_string( $args['class'] ) && '' !== trim( $args['class'] ) ) {
		foreach ( preg_split( '/\s+/', trim( $args['class'] ) ) as $extra ) {
			$classes[] = sanitize_html_class( $extra );
		}
	}
	$classes = implode( ' ', array_unique( array_filter( $classes ) ) );

	$width  = is_numeric( $args['stroke_width'] ) ? (float) $args['stroke_width'] : (float) $library['strokeWidth'];
	$width  = ( $width > 0 && $width <= 6 ) ? $width : 1.5;

	$styles = array();
	$base   = vetra_icon_sanitize_color( $args['base_color'] );
	$accent = vetra_icon_sanitize_color( $args['accent_color'] );
	if ( '' !== $base ) {
		$styles[] = '--vetra-icon-base-color:' . $base;
	}
	if ( '' !== $accent ) {
		$styles[] = '--vetra-icon-accent-color:' . $accent;
	}

	$attrs = array(
		'class'       => $classes,
		'viewBox'     => $library['viewBox'],
		'width'       => $size,
		'height'      => $size,
		'fill'        => 'none',
		'stroke-width' => (string) $width,
		'xmlns'       => 'http://www.w3.org/2000/svg',
		'focusable'   => 'false',
	);

	if ( '' !== trim( implode( ';', $styles ) ) ) {
		$attrs['style'] = implode( ';', $styles );
	}

	$accessibility = '';
	if ( $aria_hidden ) {
		$accessibility = ' aria-hidden="true"';
	} else {
		$name_attr = '' !== $label ? $label : $title;
		$attrs['role'] = 'img';
		$accessibility = ' aria-label="' . esc_attr( $name_attr ) . '"';
	}

	$attr_html = '';
	foreach ( $attrs as $attr => $value ) {
		$attr_html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
	}

	$inner = '';
	if ( '' !== $title && ! $aria_hidden ) {
		$inner .= '<title>' . esc_html( $title ) . '</title>';
	}
	$inner .= '<g class="vetra-icon-base">' . vetra_icon_inner( $icon, 'base' ) . '</g>';
	$inner .= '<g class="vetra-icon-accent">' . vetra_icon_inner( $icon, 'accent' ) . '</g>';

	return '<svg' . $attr_html . $accessibility . '>' . $inner . '</svg>';
}

/**
 * Sanitise an icon size argument to a safe CSS length.
 *
 * Accepts bare numbers (treated as px) and px/em/rem/% units.
 *
 * @param string|int|float $size Candidate size.
 * @return string
 */
function vetra_icon_sanitize_size( $size ) {
	if ( is_numeric( $size ) ) {
		$number = (float) $size;
		if ( $number > 0 && $number <= 512 ) {
			return (string) ( 0 === $number - (int) $number ? (int) $number : $number ) . 'px';
		}
		return '24px';
	}

	$size = is_string( $size ) ? trim( $size ) : '';
	if ( preg_match( '/^(\d+(?:\.\d+)?)(px|em|rem|%)$/', $size, $matches ) ) {
		$value = (float) $matches[1];
		if ( $value > 0 && $value <= 512 ) {
			return $matches[1] . $matches[2];
		}
	}

	return '24px';
}

/**
 * Backward-compatible wrapper used across the theme templates.
 *
 * @param string $name Icon name.
 * @param array  $args Optional args (see vetra_icon()).
 * @return string Escaped SVG markup.
 */
function vetra_inline_icon( $name = 'arrow', $args = array() ) {
	return vetra_icon( $name, $args );
}

/**
 * Build a data-URI SVG for admin menu icons (Dashicons replacement).
 *
 * The admin menu renders `menu_icon` as a background image, so a fixed colour
 * from the VETRA palette is used instead of `currentColor`.
 *
 * @param string $name  Icon name.
 * @param string $color Stroke colour (hex or rgb). Default raw concrete.
 * @return string
 */
function vetra_icon_data_uri( $name, $color = '#8A95A5' ) {
	$svg = vetra_icon(
		$name,
		array(
			'decorative'   => true,
			'base_color'   => $color,
			'accent_color' => $color,
			'size'         => 24,
			'rtl_flip'     => false,
		)
	);

	if ( '' === $svg ) {
		return '';
	}

	return 'data:image/svg+xml,' . rawurlencode( $svg );
}

/**
 * `[vetra_icon]` shortcode.
 *
 * Example: [vetra_icon name="check" size="20" label="تأیید شده"]
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function vetra_icon_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'         => 'info',
			'class'        => '',
			'size'         => '24',
			'label'        => '',
			'title'        => '',
			'base_color'   => '',
			'accent_color' => '',
			'decorative'   => '',
			'rtl_flip'     => 'true',
		),
		$atts,
		'vetra_icon'
	);

	$args = array(
		'class'        => $atts['class'],
		'size'         => $atts['size'],
		'label'        => $atts['label'],
		'title'        => $atts['title'],
		'base_color'   => $atts['base_color'],
		'accent_color' => $atts['accent_color'],
		'rtl_flip'     => in_array( strtolower( (string) $atts['rtl_flip'] ), array( '1', 'true', 'yes' ), true ),
	);

	if ( '' !== $atts['decorative'] ) {
		$args['decorative'] = in_array( strtolower( (string) $atts['decorative'] ), array( '1', 'true', 'yes' ), true );
	}

	return vetra_icon( $atts['name'], $args );
}
add_shortcode( 'vetra_icon', 'vetra_icon_shortcode' );

/**
 * Enqueue the icon stylesheet on the front end and in the admin.
 *
 * @return void
 */
function vetra_icons_enqueue_assets() {
	$src = defined( 'VETRA_PORTAL_URI' )
		? VETRA_PORTAL_URI . '/assets/css/vetra-icons.css'
		: ( defined( 'VETRA_ICONS_URI' ) ? VETRA_ICONS_URI . '/../css/vetra-icons.css' : '' );

	if ( '' === $src ) {
		return;
	}

	wp_enqueue_style( 'vetra-icons', $src, array(), VETRA_ICONS_VERSION );
}
add_action( 'wp_enqueue_scripts', 'vetra_icons_enqueue_assets', 20 );
add_action( 'admin_enqueue_scripts', 'vetra_icons_enqueue_assets' );
