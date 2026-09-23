<?php
/**
 * Form content model.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_register_form_content() {
	$labels = array(
		'name'               => 'فرم‌های عملیاتی',
		'singular_name'      => 'فرم عملیاتی',
		'add_new'            => 'افزودن فرم',
		'add_new_item'       => 'افزودن فرم عملیاتی',
		'edit_item'          => 'ویرایش فرم',
		'new_item'           => 'فرم جدید',
		'view_item'          => 'مشاهده فرم',
		'search_items'       => 'جستجوی فرم‌ها',
		'not_found'          => 'فرمی پیدا نشد',
		'menu_name'          => 'مرکز فرم‌ها',
	);

	register_post_type(
		'vetra_form',
		array(
			'labels'             => $labels,
			'public'             => true,
			'show_in_rest'       => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'forms', 'with_front' => false ),
			'menu_icon'          => 'dashicons-forms',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'show_in_nav_menus'  => true,
		)
	);
}
add_action( 'init', 'vetra_register_form_content' );

function vetra_register_form_taxonomy() {
	$labels = array(
		'name'          => 'دسته‌بندی فرم‌ها',
		'singular_name' => 'دسته فرم',
		'search_items'  => 'جستجوی دسته‌ها',
		'all_items'     => 'همه دسته‌ها',
		'edit_item'     => 'ویرایش دسته',
		'add_new_item'  => 'افزودن دسته',
		'menu_name'     => 'دسته‌بندی فرم‌ها',
	);

	register_taxonomy(
		'form_category',
		array( 'vetra_form' ),
		array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'form-category', 'with_front' => false ),
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'vetra_register_form_taxonomy' );

function vetra_seed_form_categories() {
	$terms = array(
		'reports'     => 'گزارشی',
		'control'     => 'کنترل و نظارت',
		'requests'    => 'درخواست‌ها',
		'finance'     => 'مالی و پیمانکاران',
	);

	foreach ( $terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'form_category' ) ) {
			wp_insert_term( $name, 'form_category', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'vetra_seed_form_categories' );
