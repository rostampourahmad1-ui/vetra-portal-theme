<?php
/**
 * Native project metadata UI.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MetaBoxes {
	private const NONCE_ACTION = 'vetra_plus_project_meta';
	private const NONCE_NAME = 'vetra_plus_project_nonce';

	public function __construct() {
		add_action( 'add_meta_boxes_vetra_project', array( $this, 'register' ) );
		add_action( 'save_post_vetra_project', array( $this, 'save' ), 10, 2 );
		add_action( 'init', array( $this, 'register_meta' ), 10 );
	}

	public function register() {
		add_meta_box( 'vetra_plus_project_details', 'اطلاعات اجرایی پروژه', array( $this, 'render' ), 'vetra_project', 'normal', 'high' );
	}

	public function render( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		$fields = array(
			'vetra_project_client' => array( 'کارفرما', 'text' ),
			'vetra_project_location' => array( 'موقعیت پروژه', 'text' ),
			'vetra_project_area' => array( 'مساحت یا زیربنا', 'text' ),
			'vetra_project_year' => array( 'سال اجرا', 'number' ),
			'vetra_project_status' => array( 'وضعیت پروژه', 'select' ),
			'vetra_project_gallery' => array( 'شناسه تصاویر گالری', 'text' ),
		);
		echo '<div dir="rtl" class="vetra-plus-project-fields">';
		foreach ( $fields as $key => $field ) {
			$value = get_post_meta( $post->ID, '_' . $key, true );
			echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label><br>';
			if ( 'select' === $field[1] ) {
				echo '<select class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
				foreach ( array( 'planned' => 'در برنامه', 'active' => 'در حال اجرا', 'completed' => 'تکمیل‌شده', 'archived' => 'آرشیو' ) as $option => $label ) {
					echo '<option value="' . esc_attr( $option ) . '" ' . selected( $value ?: 'planned', $option, false ) . '>' . esc_html( $label ) . '</option>';
				}
				echo '</select>';
			} else {
				echo '<input class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field[1] ) . '" value="' . esc_attr( $value ) . '" />';
			}
			echo '</p>';
		}
		echo '<p class="description">شناسه‌های تصاویر گالری را با کاما جدا کنید؛ فقط IDهای attachment ذخیره می‌شوند.</p></div>';
	}

	public function save( $post_id, $post ) {
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || 'vetra_project' !== $post->post_type || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$text_fields = array( 'vetra_project_client', 'vetra_project_location', 'vetra_project_area' );
		foreach ( $text_fields as $key ) {
			update_post_meta( $post_id, '_' . $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ?? '' ) ) );
		}
		update_post_meta( $post_id, '_vetra_project_year', absint( $_POST['vetra_project_year'] ?? 0 ) );
		$status = sanitize_key( wp_unslash( $_POST['vetra_project_status'] ?? 'planned' ) );
		update_post_meta( $post_id, '_vetra_project_status', in_array( $status, array( 'planned', 'active', 'completed', 'archived' ), true ) ? $status : 'planned' );
		$gallery = array_filter( array_map( 'absint', preg_split( '/[,\s]+/', sanitize_text_field( wp_unslash( $_POST['vetra_project_gallery'] ?? '' ) ) ) ) );
		update_post_meta( $post_id, '_vetra_project_gallery', array_values( array_unique( $gallery ) ) );
	}

	public function register_meta() {
		foreach ( array( 'client', 'location', 'area', 'year', 'status', 'gallery' ) as $key ) {
			register_post_meta( 'vetra_project', '_vetra_project_' . $key, array(
				'show_in_rest' => true,
				'single' => true,
				'auth_callback' => function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
				'sanitize_callback' => 'gallery' === $key ? array( $this, 'sanitize_gallery' ) : ( 'year' === $key ? 'absint' : 'sanitize_text_field' ),
			) );
		}
	}

	public function sanitize_gallery( $value ) {
		$values = is_array( $value ) ? $value : preg_split( '/[,\s]+/', (string) $value );
		return array_values( array_unique( array_filter( array_map( 'absint', $values ) ) ) );
	}
}
