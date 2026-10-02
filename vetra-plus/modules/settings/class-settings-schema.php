<?php
/**
 * Lightweight Vetra Plus settings schema.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SettingsSchema {
	public static function version(): int { return 1; }

	public static function categories(): array {
		return array(
			'identity' => 'هویت بصری و رنگ‌ها',
			'typography' => 'تایپوگرافی',
			'header' => 'هدر و ناوبری',
			'footer' => 'فوتر',
			'custom' => 'کدهای سفارشی',
		);
	}

	public static function fields(): array {
		return array(
			'identity' => array(
				'vetra_brand_title' => array( 'label' => 'نام برند', 'type' => 'text', 'default' => 'وترا' ),
				'vetra_brand_subtitle' => array( 'label' => 'شعار برند', 'type' => 'text', 'default' => 'معماری، مهندسی و ساخت' ),
				'vetra_primary_color' => array( 'label' => 'رنگ اصلی', 'type' => 'color', 'default' => '#C67D34' ),
				'vetra_secondary_color' => array( 'label' => 'رنگ مکمل', 'type' => 'color', 'default' => '#B3803B' ),
				'vetra_logo_id' => array( 'label' => 'شناسه لوگو', 'type' => 'integer', 'default' => 0, 'min' => 0, 'max' => PHP_INT_MAX ),
			),
			'typography' => array(
				'vetra_font_family' => array( 'label' => 'فونت بدنه', 'type' => 'text', 'default' => 'Vazirmatn, Tahoma, sans-serif' ),
				'vetra_heading_font' => array( 'label' => 'فونت تیترها', 'type' => 'text', 'default' => 'inherit' ),
				'vetra_load_vazirmatn' => array( 'label' => 'بارگذاری Vazirmatn', 'type' => 'boolean', 'default' => false ),
			),
			'header' => array(
				'vetra_header_enabled' => array( 'label' => 'فعال‌سازی هدر', 'type' => 'boolean', 'default' => true ),
				'vetra_header_sticky' => array( 'label' => 'هدر چسبان', 'type' => 'boolean', 'default' => true ),
				'vetra_header_cta_text' => array( 'label' => 'متن CTA هدر', 'type' => 'text', 'default' => 'شروع همکاری' ),
				'vetra_header_cta_url' => array( 'label' => 'لینک CTA هدر', 'type' => 'url', 'default' => '#contact' ),
			),
			'footer' => array(
				'vetra_footer_enabled' => array( 'label' => 'فعال‌سازی فوتر', 'type' => 'boolean', 'default' => true ),
				'vetra_footer_text' => array( 'label' => 'متن فوتر', 'type' => 'textarea', 'default' => 'طراحی دقیق. ساخت ماندگار.' ),
				'vetra_contact_email' => array( 'label' => 'ایمیل تماس', 'type' => 'email', 'default' => 'hello@vetragroup.ir' ),
			),
			'custom' => array(
				'vetra_custom_css' => array( 'label' => 'CSS سفارشی', 'type' => 'css', 'default' => '' ),
			),
		);
	}

	public static function flat_fields(): array {
		$fields = array();
		foreach ( self::fields() as $category ) {
			$fields = array_merge( $fields, $category );
		}
		return $fields;
	}

	public static function defaults(): array {
		$defaults = array();
		foreach ( self::flat_fields() as $key => $field ) {
			$defaults[ $key ] = $field['default'];
		}
		return $defaults;
	}
}
