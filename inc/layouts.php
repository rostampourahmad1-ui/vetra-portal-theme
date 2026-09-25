<?php
/**
 * Reusable header/footer layouts and per-page assignment.
 *
 * Layouts are ordinary WordPress content managed by administrators. They are
 * deliberately separate from the projects data store.
 *
 * @package VetraPortal
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function vetra_register_layout_post_type() {
	register_post_type( 'vetra_layout', array(
		'labels' => array(
			'name' => 'الگوهای هدر و فوتر',
			'singular_name' => 'الگوی هدر/فوتر',
			'add_new_item' => 'افزودن الگوی هدر یا فوتر',
			'edit_item' => 'ویرایش الگوی هدر یا فوتر',
		),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => 'themes.php',
		'show_in_rest' => true,
		'menu_icon' => 'dashicons-layout',
		'supports' => array( 'title', 'editor', 'revisions' ),
		'capability_type' => 'post',
		'map_meta_cap' => true,
	) );
}
add_action( 'init', 'vetra_register_layout_post_type' );

function vetra_layout_types() {
	return array( 'header' => 'هدر', 'footer' => 'فوتر' );
}

function vetra_layout_meta_boxes() {
	add_meta_box( 'vetra_layout_type', 'نوع الگو', 'vetra_layout_type_meta_box', 'vetra_layout', 'side' );
	add_meta_box( 'vetra_page_layouts', 'انتخاب هدر و فوتر وترا', 'vetra_page_layouts_meta_box', 'page', 'side' );
}
add_action( 'add_meta_boxes', 'vetra_layout_meta_boxes' );

function vetra_layout_type_meta_box( $post ) {
	wp_nonce_field( 'vetra_layout_meta', 'vetra_layout_meta_nonce' );
	$current = get_post_meta( $post->ID, '_vetra_layout_type', true );
	if ( ! $current ) { $current = 'header'; }
	echo '<p><label for="vetra-layout-type">نوع الگو</label></p><select id="vetra-layout-type" name="vetra_layout_type" style="width:100%">';
	foreach ( vetra_layout_types() as $value => $label ) {
		printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $current, $value, false ), esc_html( $label ) );
	}
	echo '</select><p class="description">محتوای این الگو پس از ذخیره در محل انتخاب‌شده نمایش داده می‌شود.</p>';
}

function vetra_layout_choices( $type = '' ) {
	$choices = array( 0 => 'بدون الگوی اختصاصی' );
	$posts = get_posts( array( 'post_type' => 'vetra_layout', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
	foreach ( $posts as $post ) {
		if ( $type && $type !== get_post_meta( $post->ID, '_vetra_layout_type', true ) ) { continue; }
		$choices[ $post->ID ] = $post->post_title;
	}
	return $choices;
}

function vetra_page_layouts_meta_box( $post ) {
	wp_nonce_field( 'vetra_page_layouts', 'vetra_page_layouts_nonce' );
	$header = absint( get_post_meta( $post->ID, '_vetra_header_layout', true ) );
	$footer = absint( get_post_meta( $post->ID, '_vetra_footer_layout', true ) );
	foreach ( array( 'header' => array( 'عنوان' => 'هدر', 'value' => $header ), 'footer' => array( 'عنوان' => 'فوتر', 'value' => $footer ) ) as $type => $field ) {
		echo '<p><label for="vetra-' . esc_attr( $type ) . '-layout">' . esc_html( $field['عنوان'] ) . '</label><select id="vetra-' . esc_attr( $type ) . '-layout" name="vetra_' . esc_attr( $type ) . '_layout" style="width:100%">';
		foreach ( vetra_layout_choices( $type ) as $id => $label ) { printf( '<option value="%d" %s>%s</option>', absint( $id ), selected( $field['value'], $id, false ), esc_html( $label ) ); }
		echo '</select></p>';
	}
	echo '<p class="description">در صورت انتخاب‌نکردن، تنظیم پیش‌فرض سفارشی‌سازی استفاده می‌شود.</p>';
}

function vetra_save_layout_meta( $post_id ) {
	if ( isset( $_POST['vetra_layout_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_layout_meta_nonce'] ) ), 'vetra_layout_meta' ) && current_user_can( 'edit_post', $post_id ) ) {
		$type = sanitize_key( wp_unslash( $_POST['vetra_layout_type'] ?? '' ) );
		if ( isset( vetra_layout_types()[ $type ] ) ) { update_post_meta( $post_id, '_vetra_layout_type', $type ); }
	}
	if ( isset( $_POST['vetra_page_layouts_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_page_layouts_nonce'] ) ), 'vetra_page_layouts' ) && current_user_can( 'edit_page', $post_id ) ) {
		foreach ( array( 'header', 'footer' ) as $type ) {
			$id = absint( $_POST[ 'vetra_' . $type . '_layout' ] ?? 0 );
			$valid = $id && 'publish' === get_post_status( $id ) && $type === get_post_meta( $id, '_vetra_layout_type', true );
			if ( $valid ) { update_post_meta( $post_id, '_vetra_' . $type . '_layout', $id ); } else { delete_post_meta( $post_id, '_vetra_' . $type . '_layout' ); }
		}
	}
}
add_action( 'save_post_vetra_layout', 'vetra_save_layout_meta' );
add_action( 'save_post_page', 'vetra_save_layout_meta', 20 );

function vetra_layout_option_key( $type ) { return 'default_' . sanitize_key( $type ) . '_layout'; }
function vetra_selected_layout_id( $type, $post_id = 0 ) {
	$post_id = $post_id ?: ( is_singular() ? get_queried_object_id() : 0 );
	$id = $post_id ? absint( get_post_meta( $post_id, '_vetra_' . $type . '_layout', true ) ) : 0;
	if ( ! $id ) { $id = absint( vetra_option( vetra_layout_option_key( $type ), 0 ) ); }
	return $id && 'publish' === get_post_status( $id ) && $type === get_post_meta( $id, '_vetra_layout_type', true ) ? $id : 0;
}

function vetra_render_layout( $type ) {
	$id = vetra_selected_layout_id( $type );
	if ( ! $id ) { return false; }
	$content = apply_filters( 'the_content', get_post_field( 'post_content', $id ) );
	if ( '' === trim( wp_strip_all_tags( $content ) ) ) { return false; }
	echo '<div class="vetra-layout vetra-layout--' . esc_attr( $type ) . '" data-vetra-layout="' . absint( $id ) . '">' . $content . '</div>';
	return true;
}

function vetra_layout_customizer_register( $wp_customize ) {
	$wp_customize->add_section( 'vetra_layout_section', array( 'title' => 'هدر و فوترهای قابل انتخاب', 'description' => 'الگوها را از نمایش > الگوهای هدر و فوتر بسازید و سپس پیش‌فرض کلی را تعیین کنید.', 'panel' => 'vetra_corporate_panel', 'priority' => 32 ) );
	foreach ( array( 'header' => 'هدر پیش‌فرض', 'footer' => 'فوتر پیش‌فرض' ) as $type => $label ) {
		$key = vetra_layout_option_key( $type );
		$wp_customize->add_setting( 'vetra_' . $key, array( 'default' => 0, 'sanitize_callback' => 'absint', 'transport' => 'refresh' ) );
		$wp_customize->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => 'vetra_layout_section', 'type' => 'select', 'choices' => vetra_layout_choices( $type ) ) );
	}
}
add_action( 'customize_register', 'vetra_layout_customizer_register', 20 );
