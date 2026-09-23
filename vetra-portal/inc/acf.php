<?php
/**
 * ACF Pro local field group.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_vetra_form_details',
			'title'    => 'مشخصات فرم عملیاتی وترا',
			'fields'   => array(
				array( 'key' => 'field_vetra_form_code', 'label' => 'کد استاندارد فرم', 'name' => 'form_code', 'type' => 'text', 'required' => 1, 'placeholder' => 'FR-QC-102' ),
				array( 'key' => 'field_vetra_form_description', 'label' => 'توضیح مختصر', 'name' => 'form_description', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'br' ),
				array( 'key' => 'field_vetra_form_icon', 'label' => 'آیکون فرم', 'name' => 'form_icon', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'library' => 'all' ),
				array( 'key' => 'field_vetra_form_badge', 'label' => 'نشان وضعیت', 'name' => 'form_badge', 'type' => 'text', 'placeholder' => 'روزانه' ),
				array( 'key' => 'field_vetra_form_url', 'label' => 'لینک تکمیل فرم', 'name' => 'form_url', 'type' => 'url', 'required' => 1, 'placeholder' => 'https://forms.example.com/...' ),
				array( 'key' => 'field_vetra_form_completion_time', 'label' => 'میانگین زمان تکمیل', 'name' => 'form_completion_time', 'type' => 'text', 'placeholder' => '۵ دقیقه' ),
				array( 'key' => 'field_vetra_form_output_format', 'label' => 'فرمت خروجی', 'name' => 'form_output_format', 'type' => 'select', 'choices' => array( 'PDF' => 'PDF', 'Excel' => 'Excel', 'PDF / Excel' => 'PDF / Excel', 'Online' => 'آنلاین' ), 'default_value' => 'PDF', 'allow_null' => 0, 'ui' => 1 ),
				array( 'key' => 'field_vetra_form_required_role', 'label' => 'نقش مجاز', 'name' => 'form_required_role', 'type' => 'select', 'choices' => array( 'all' => 'همه کاربران', 'subscriber' => 'کاربر عادی', 'author' => 'کارشناس', 'editor' => 'مدیر پروژه', 'administrator' => 'مدیر سیستم' ), 'default_value' => 'all', 'ui' => 1 ),
				array( 'key' => 'field_vetra_form_order', 'label' => 'ترتیب نمایش', 'name' => 'form_order', 'type' => 'number', 'default_value' => 0, 'min' => 0 ),
				array( 'key' => 'field_vetra_form_active', 'label' => 'فعال است', 'name' => 'form_is_active', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1 ),
			),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vetra_form' ) ) ),
			'position' => 'normal',
			'active'   => true,
		)
	);
}
add_action( 'acf/init', 'vetra_register_acf_fields' );
