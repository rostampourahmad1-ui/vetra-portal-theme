<?php
/**
 * Vetra Portal theme bootstrap.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VETRA_PORTAL_VERSION', '1.0.0' );
define( 'VETRA_PORTAL_DIR', get_template_directory() );
define( 'VETRA_PORTAL_URI', get_template_directory_uri() );

require_once VETRA_PORTAL_DIR . '/inc/helpers.php';
require_once VETRA_PORTAL_DIR . '/inc/cpt.php';
require_once VETRA_PORTAL_DIR . '/inc/acf.php';

function vetra_portal_setup() {
	load_theme_textdomain( 'vetra-portal', VETRA_PORTAL_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-background', array( 'default-color' => '0f172a' ) );
	add_theme_support( 'align-wide' );

	register_nav_menus(
		array(
			'primary' => __( 'منوی اصلی پرتال', 'vetra-portal' ),
		)
	);
}
add_action( 'after_setup_theme', 'vetra_portal_setup' );

function vetra_portal_enqueue_assets() {
	wp_enqueue_style( 'vetra-portal-style', get_stylesheet_uri(), array(), VETRA_PORTAL_VERSION );
	wp_enqueue_style( 'vetra-portal-layout', VETRA_PORTAL_URI . '/assets/css/portal.css', array( 'vetra-portal-style' ), VETRA_PORTAL_VERSION );
	wp_enqueue_script( 'vetra-portal-script', VETRA_PORTAL_URI . '/assets/js/portal.js', array(), VETRA_PORTAL_VERSION, true );
	wp_localize_script(
		'vetra-portal-script',
		'vetraPortal',
		array(
			'noResults' => __( 'فرمی با این مشخصات پیدا نشد.', 'vetra-portal' ),
			'openMenu' => __( 'باز کردن منو', 'vetra-portal' ),
			'closeMenu' => __( 'بستن منو', 'vetra-portal' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vetra_portal_enqueue_assets' );

function vetra_portal_body_classes( $classes ) {
	$classes[] = 'vetra-portal-theme';
	$classes[] = is_user_logged_in() ? 'vetra-user-logged-in' : 'vetra-user-logged-out';

	if ( is_post_type_archive( 'vetra_form' ) || is_singular( 'vetra_form' ) ) {
		$classes[] = 'vetra-forms-context';
	}

	return $classes;
}
add_filter( 'body_class', 'vetra_portal_body_classes' );

function vetra_portal_excerpt_length() {
	return 18;
}
add_filter( 'excerpt_length', 'vetra_portal_excerpt_length' );

function vetra_portal_flush_rewrites() {
	vetra_register_form_content();
	vetra_register_form_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'vetra_portal_flush_rewrites' );
