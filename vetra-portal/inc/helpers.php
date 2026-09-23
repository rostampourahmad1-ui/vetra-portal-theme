<?php
/**
 * Theme helpers.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_field( $key, $post_id = false, $default = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $key, $post_id ) : get_post_meta( $post_id ?: get_the_ID(), $key, true );

	return ( '' === $value || null === $value || false === $value ) ? $default : $value;
}

function vetra_form_icon_url( $value ) {
	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		return $value['url'];
	}

	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, 'thumbnail' );
		return $url ? $url : '';
	}

	return is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) ? $value : '';
}

function vetra_inline_icon( $name = 'file' ) {
	$icons = array(
		'file'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.5h7.2L19 8.3V20.5H7A2.5 2.5 0 0 1 4.5 18V6A2.5 2.5 0 0 1 7 3.5Zm6.5 1.8V9h3.7M8 12.5h7M8 16h5"/></svg>',
		'chart'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19.5V11m7 8.5V5m7 14.5v-6M3.5 19.5h17"/></svg>',
		'calendar' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="15" rx="2"/><path d="M8 3.5v4M16 3.5v4M4 10h16M8 14h.01M12 14h.01M16 14h.01M8 17.5h.01M12 17.5h.01"/></svg>',
		'check'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="m8 12 2.5 2.5L16 9"/></svg>',
		'building' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 20.5V7l7-3.5 7 3.5v13.5M9 20.5v-4h6v4M8 10h.01M12 10h.01M16 10h.01M8 13h.01M12 13h.01M16 13h.01"/></svg>',
		'users'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.2"/><path d="M3.5 19c.7-3 2.5-4.5 5.5-4.5s4.8 1.5 5.5 4.5M15 14.5c2.8-.1 4.5 1.3 5 3.8"/></svg>',
		'wallet'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h15a1.5 1.5 0 0 1 1.5 1.5v9A2.5 2.5 0 0 1 18 20.5H6A2.5 2.5 0 0 1 3.5 18V6A2.5 2.5 0 0 1 6 3.5h11M16 13h4.5M16 13a1.5 1.5 0 1 0 0 3h4.5"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['file'];
}

function vetra_render_form_icon( $value = '', $fallback = 'file' ) {
	$url = vetra_form_icon_url( $value );

	if ( $url ) {
		return '<img src="' . esc_url( $url ) . '" alt="" loading="lazy" />';
	}

	return vetra_inline_icon( $fallback );
}

function vetra_form_terms( $post_id ) {
	$terms = get_the_terms( $post_id, 'form_category' );
	return is_wp_error( $terms ) || ! $terms ? array() : $terms;
}

function vetra_user_can_view_form( $post_id ) {
	$required_role = vetra_field( 'form_required_role', $post_id, 'all' );

	if ( 'all' === $required_role || ! $required_role ) {
		return true;
	}

	if ( ! is_user_logged_in() ) {
		return false;
	}

	$user = wp_get_current_user();
	return in_array( $required_role, (array) $user->roles, true ) || current_user_can( $required_role );
}

function vetra_form_url( $post_id ) {
	$url = vetra_field( 'form_url', $post_id, '' );
	return $url ? esc_url( $url ) : esc_url( get_permalink( $post_id ) );
}

function vetra_get_dashboard_stat( $key, $fallback ) {
	return apply_filters( 'vetra_dashboard_stat_' . $key, $fallback );
}

function vetra_get_dashboard_nav() {
	$forms_url = get_post_type_archive_link( 'vetra_form' );

	return array(
		array( 'label' => 'داشبورد', 'url' => home_url( '/' ), 'icon' => 'grid', 'active' => is_front_page() ),
		array( 'label' => 'پروژه‌ها', 'url' => '#projects', 'icon' => 'building' ),
		array( 'label' => 'برنامه زمان‌بندی', 'url' => '#schedule', 'icon' => 'calendar' ),
		array( 'label' => 'منابع و ماشین‌آلات', 'url' => '#resources', 'icon' => 'chart' ),
		array( 'label' => 'مالی و قراردادها', 'url' => '#finance', 'icon' => 'wallet' ),
		array( 'label' => 'اسناد و مدارک', 'url' => '#documents', 'icon' => 'file' ),
		array( 'label' => 'مرکز فرم‌ها', 'url' => $forms_url, 'icon' => 'forms', 'active' => is_post_type_archive( 'vetra_form' ) || is_singular( 'vetra_form' ) ),
		array( 'label' => 'گزارش‌ها', 'url' => '#reports', 'icon' => 'chart' ),
		array( 'label' => 'کاربران', 'url' => '#users', 'icon' => 'users' ),
	);
}

function vetra_nav_icon( $name ) {
	$icons = array(
		'grid'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/></svg>',
		'building' => vetra_inline_icon( 'building' ),
		'calendar' => vetra_inline_icon( 'calendar' ),
		'chart'    => vetra_inline_icon( 'chart' ),
		'file'     => vetra_inline_icon( 'file' ),
		'forms'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.5h7.5L19 8v12.5H7A2.5 2.5 0 0 1 4.5 18V6A2.5 2.5 0 0 1 7 3.5ZM14 4v4h4M8 12h8M8 15.5h6"/></svg>',
		'users'    => vetra_inline_icon( 'users' ),
		'wallet'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h15a1.5 1.5 0 0 1 1.5 1.5v9A2.5 2.5 0 0 1 18 20.5H6A2.5 2.5 0 0 1 3.5 18V6A2.5 2.5 0 0 1 6 3.5h11M16 13h4.5M16 13a1.5 1.5 0 1 0 0 3h4.5"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['grid'];
}
