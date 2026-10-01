<?php
/**
 * Vetra Plus project API and theme adapter hooks.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class API {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_filter( 'vetra_plus_project_data', array( $this, 'normalize_project_data' ), 10, 2 );
	}

	public function register_routes() {
		register_rest_route( 'vetra/v1', '/projects', array(
			'methods' => \WP_REST_Server::READABLE,
			'callback' => array( $this, 'projects' ),
			'permission_callback' => '__return_true',
			'args' => array(
				'per_page' => array( 'default' => 12, 'sanitize_callback' => 'absint', 'validate_callback' => function ( $value ) { return $value >= 1 && $value <= 50; } ),
				'page' => array( 'default' => 1, 'sanitize_callback' => 'absint', 'validate_callback' => function ( $value ) { return $value >= 1; } ),
			),
		) );
	}

	public function projects( \WP_REST_Request $request ) {
		$query = new \WP_Query( array(
			'post_type' => 'vetra_project',
			'post_status' => 'publish',
			'posts_per_page' => min( 50, max( 1, absint( $request['per_page'] ) ) ),
			'paged' => max( 1, absint( $request['page'] ) ),
			'no_found_rows' => false,
		) );
		$items = array_map( array( $this, 'serialize_project' ), $query->posts );
		$response = new \WP_REST_Response( array( 'items' => $items, 'total' => (int) $query->found_posts, 'pages' => (int) $query->max_num_pages ) );
		$response->header( 'X-Vetra-Plus-Version', VETRA_PLUS_VERSION );
		return $response;
	}

	public function serialize_project( $post ) {
		$data = array(
			'id' => (int) $post->ID,
			'slug' => $post->post_name,
			'title' => get_the_title( $post ),
			'excerpt' => get_the_excerpt( $post ),
			'content' => apply_filters( 'the_content', $post->post_content ),
			'link' => get_permalink( $post ),
			'featured_image' => get_the_post_thumbnail_url( $post, 'large' ) ?: '',
			'client' => get_post_meta( $post->ID, '_vetra_project_client', true ),
			'location' => get_post_meta( $post->ID, '_vetra_project_location', true ),
			'area' => get_post_meta( $post->ID, '_vetra_project_area', true ),
			'year' => absint( get_post_meta( $post->ID, '_vetra_project_year', true ) ),
			'status' => get_post_meta( $post->ID, '_vetra_project_status', true ) ?: 'planned',
			'gallery' => array_values( array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_vetra_project_gallery', true ) ) ) ),
			'categories' => wp_get_post_terms( $post->ID, 'vetra_project_category', array( 'fields' => 'names' ) ),
			'skills' => wp_get_post_terms( $post->ID, 'vetra_project_skill', array( 'fields' => 'names' ) ),
			'features' => wp_get_post_terms( $post->ID, 'vetra_project_feature', array( 'fields' => 'names' ) ),
		);
		return apply_filters( 'vetra_plus_project_data', $data, $post );
	}

	public function normalize_project_data( $data, $post ) {
		$data['id'] = absint( $data['id'] ?? 0 );
		$data['title'] = sanitize_text_field( $data['title'] ?? '' );
		$data['client'] = sanitize_text_field( $data['client'] ?? '' );
		$data['location'] = sanitize_text_field( $data['location'] ?? '' );
		$data['status'] = sanitize_key( $data['status'] ?? 'planned' );
		$data['gallery'] = array_values( array_filter( array_map( 'absint', (array) ( $data['gallery'] ?? array() ) ) ) );
		return $data;
	}
}
