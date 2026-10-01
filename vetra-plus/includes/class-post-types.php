<?php
/**
 * Vetra Plus post types and taxonomies.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PostTypes {
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register' ), 5 );
	}

	public static function register() {
		register_post_type(
			'vetra_project',
			array(
				'labels' => array(
					'name' => 'پروژه‌ها',
					'singular_name' => 'پروژه وترا',
					'add_new' => 'افزودن پروژه',
					'add_new_item' => 'افزودن پروژه جدید',
					'edit_item' => 'ویرایش پروژه',
					'new_item' => 'پروژه جدید',
					'view_item' => 'مشاهده پروژه',
					'search_items' => 'جست‌وجوی پروژه‌ها',
					'not_found' => 'پروژه‌ای پیدا نشد',
					'menu_name' => 'پروژه‌های وترا',
				),
				'public' => true,
				'show_ui' => true,
				'show_in_rest' => true,
				'has_archive' => true,
				'rewrite' => array( 'slug' => 'projects', 'with_front' => false ),
				'menu_icon' => 'dashicons-building',
				'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
				'taxonomies' => array( 'vetra_project_category', 'vetra_project_skill', 'vetra_project_feature' ),
				'capability_type' => array( 'vetra_project', 'vetra_projects' ),
				'map_meta_cap' => true,
				'capabilities' => array(
					'edit_post' => 'edit_vetra_project',
					'read_post' => 'read_vetra_project',
					'delete_post' => 'delete_vetra_project',
					'edit_posts' => 'edit_vetra_projects',
					'edit_others_posts' => 'edit_others_vetra_projects',
					'publish_posts' => 'publish_vetra_projects',
					'read_private_posts' => 'read_private_vetra_projects',
					'delete_posts' => 'delete_vetra_projects',
					'delete_private_posts' => 'delete_private_vetra_projects',
					'delete_published_posts' => 'delete_published_vetra_projects',
					'edit_private_posts' => 'edit_private_vetra_projects',
					'edit_published_posts' => 'edit_published_vetra_projects',
				),
			)
		);

		register_taxonomy(
			'vetra_project_category',
			'vetra_project',
			array(
				'labels' => array( 'name' => 'دسته‌های پروژه', 'singular_name' => 'دسته پروژه', 'menu_name' => 'دسته‌ها' ),
				'public' => true,
				'show_in_rest' => true,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => 'project-category', 'with_front' => false ),
				'capabilities' => array( 'manage_terms' => 'manage_vetra_project_terms', 'edit_terms' => 'manage_vetra_project_terms', 'delete_terms' => 'manage_vetra_project_terms', 'assign_terms' => 'edit_vetra_project' ),
			)
		);

		foreach ( array( 'vetra_project_skill' => 'مهارت‌ها و تخصص‌ها', 'vetra_project_feature' => 'ویژگی‌های پروژه' ) as $taxonomy => $label ) {
			register_taxonomy( $taxonomy, 'vetra_project', array(
				'labels' => array( 'name' => $label, 'singular_name' => $label, 'menu_name' => $label ),
				'public' => true,
				'show_in_rest' => true,
				'hierarchical' => false,
				'rewrite' => array( 'slug' => str_replace( 'vetra_', '', $taxonomy ), 'with_front' => false ),
				'capabilities' => array( 'manage_terms' => 'manage_vetra_project_terms', 'edit_terms' => 'manage_vetra_project_terms', 'delete_terms' => 'manage_vetra_project_terms', 'assign_terms' => 'edit_vetra_project' ),
			) );
		}
	}
}
