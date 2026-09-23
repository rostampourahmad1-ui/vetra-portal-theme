<?php
/**
 * Vetra Portal Customizer configuration.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_customizer_defaults() {
	return array(
		'brand_title'             => 'پرتال مدیریت ساخت',
		'brand_subtitle'          => 'گروه ساختمانی وترا',
		'footer_text'             => 'ساخت بهتر، با مدیریت دقیق‌تر',
		'background_color'        => '#0f172a',
		'panel_color'             => '#1e293b',
		'accent_color'            => '#f59e0b',
		'accent_deep_color'       => '#d97706',
		'text_color'              => '#f8fafc',
		'muted_color'             => '#94a3b8',
		'line_color'              => 'rgba(255,255,255,0.09)',
		'font_family'             => 'IRANYekan, Vazirmatn, Tahoma, sans-serif',
		'base_font_size'          => 15,
		'content_width'           => 1280,
		'card_radius'             => 16,
		'glass_blur'              => 18,
		'show_notifications'      => true,
		'show_user_chip'          => true,
		'topbar_sticky'           => true,
		'forms_title'              => 'مرکز فرم‌ها و فرآیندهای عملیاتی',
		'forms_subtitle'           => 'دسترسی سریع به چک‌لیست‌ها، گزارش‌های روزانه کارگاه و درخواست‌های اداری و فنی.',
		'forms_search_placeholder' => 'جستجوی نام فرم، کد یا دسته‌بندی ...',
		'forms_grid_columns'       => 3,
		'show_form_metadata'       => true,
		'form_button_text'         => 'تکمیل و ارسال فرم',
		'custom_css'              => '',
	);
}

function vetra_option( $key, $default = null ) {
	$defaults = vetra_customizer_defaults();
	$fallback = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : $default;
	$value    = get_theme_mod( 'vetra_' . $key, $fallback );

	return ( '' === $value || null === $value ) && null !== $default ? $default : $value;
}

function vetra_sanitize_checkbox( $value ) {
	return (bool) $value;
}

function vetra_sanitize_number( $value, $min = 0, $max = 5000 ) {
	$value = absint( $value );
	return max( $min, min( $max, $value ) );
}

function vetra_sanitize_font( $value ) {
	$allowed = array(
		'IRANYekan, Vazirmatn, Tahoma, sans-serif',
		'Vazirmatn, Tahoma, sans-serif',
		'IRANSans, Tahoma, sans-serif',
		'Tahoma, Arial, sans-serif',
		'system-ui, sans-serif',
	);

	return in_array( $value, $allowed, true ) ? $value : $allowed[0];
}

function vetra_sanitize_css_value( $value ) {
	$value = trim( (string) $value );
	return preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([^)]*\)|hsla?\([^)]*\))$/', $value ) ? $value : '';
}

function vetra_sanitize_custom_css( $value ) {
	return wp_strip_all_tags( (string) $value );
}

function vetra_customizer_register( $wp_customize ) {
	$wp_customize->add_panel(
		'vetra_portal_panel',
		array(
			'title'       => 'تنظیمات پرتال وترا',
			'description' => 'کنترل کامل ظاهر، چیدمان و مرکز فرم‌های پرتال.',
			'priority'    => 25,
		)
	);

	$wp_customize->add_section( 'vetra_brand_section', array( 'title' => 'هویت و برند', 'panel' => 'vetra_portal_panel', 'priority' => 10 ) );
	vetra_add_text_setting( $wp_customize, 'brand_title', 'عنوان برند', 'vetra_brand_section', 10 );
	vetra_add_text_setting( $wp_customize, 'brand_subtitle', 'زیرعنوان برند', 'vetra_brand_section', 20 );
	vetra_add_text_setting( $wp_customize, 'footer_text', 'متن سمت چپ فوتر', 'vetra_brand_section', 30 );

	$wp_customize->add_setting( 'vetra_logo', array( 'default' => '', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'vetra_logo', array( 'label' => 'لوگوی پرتال', 'section' => 'vetra_brand_section', 'mime_type' => 'image', 'priority' => 40 ) ) );

	$wp_customize->add_section( 'vetra_colors_section', array( 'title' => 'رنگ‌ها و سطح‌ها', 'panel' => 'vetra_portal_panel', 'priority' => 20 ) );
	vetra_add_color_setting( $wp_customize, 'background_color', 'پس‌زمینه اصلی', 'vetra_colors_section', 10 );
	vetra_add_color_setting( $wp_customize, 'panel_color', 'رنگ پنل‌ها', 'vetra_colors_section', 20 );
	vetra_add_color_setting( $wp_customize, 'accent_color', 'رنگ شاخص طلایی', 'vetra_colors_section', 30 );
	vetra_add_color_setting( $wp_customize, 'accent_deep_color', 'رنگ طلایی تیره', 'vetra_colors_section', 40 );
	vetra_add_color_setting( $wp_customize, 'text_color', 'رنگ متن اصلی', 'vetra_colors_section', 50 );
	vetra_add_color_setting( $wp_customize, 'muted_color', 'رنگ متن فرعی', 'vetra_colors_section', 60 );
	vetra_add_text_setting( $wp_customize, 'line_color', 'رنگ حاشیه پنل‌ها', 'vetra_colors_section', 70, 'vetra_sanitize_css_value' );

	$wp_customize->add_section( 'vetra_typography_section', array( 'title' => 'تایپوگرافی', 'panel' => 'vetra_portal_panel', 'priority' => 30 ) );
	$wp_customize->add_setting( 'vetra_font_family', array( 'default' => vetra_customizer_defaults()['font_family'], 'sanitize_callback' => 'vetra_sanitize_font', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_font_family', array( 'label' => 'فونت اصلی', 'section' => 'vetra_typography_section', 'type' => 'select', 'choices' => array( 'IRANYekan, Vazirmatn, Tahoma, sans-serif' => 'IRANYekan / Vazirmatn', 'Vazirmatn, Tahoma, sans-serif' => 'Vazirmatn', 'IRANSans, Tahoma, sans-serif' => 'IRANSans', 'Tahoma, Arial, sans-serif' => 'Tahoma', 'system-ui, sans-serif' => 'System UI' ), 'priority' => 10 ) );
	vetra_add_number_setting( $wp_customize, 'base_font_size', 'اندازه پایه متن', 'vetra_typography_section', 20, 11, 20 );

	$wp_customize->add_section( 'vetra_layout_section', array( 'title' => 'چیدمان و جلوه‌ها', 'panel' => 'vetra_portal_panel', 'priority' => 40 ) );
	vetra_add_number_setting( $wp_customize, 'content_width', 'حداکثر عرض محتوا', 'vetra_layout_section', 10, 960, 1800 );
	vetra_add_number_setting( $wp_customize, 'card_radius', 'شعاع گوشه کارت‌ها', 'vetra_layout_section', 20, 0, 32 );
	vetra_add_number_setting( $wp_customize, 'glass_blur', 'میزان Blur شیشه‌ای', 'vetra_layout_section', 30, 0, 40 );
	vetra_add_checkbox_setting( $wp_customize, 'topbar_sticky', 'هدر چسبان باشد', 'vetra_layout_section', 40 );
	
	$wp_customize->add_section( 'vetra_navigation_section', array( 'title' => 'هدر و ناوبری', 'panel' => 'vetra_portal_panel', 'priority' => 50 ) );
	vetra_add_checkbox_setting( $wp_customize, 'show_notifications', 'نمایش اعلان‌ها', 'vetra_navigation_section', 10 );
	vetra_add_checkbox_setting( $wp_customize, 'show_user_chip', 'نمایش پروفایل کاربر', 'vetra_navigation_section', 20 );

	$wp_customize->add_section( 'vetra_forms_section', array( 'title' => 'مرکز فرم‌ها', 'panel' => 'vetra_portal_panel', 'priority' => 70 ) );
	vetra_add_text_setting( $wp_customize, 'forms_title', 'عنوان صفحه فرم‌ها', 'vetra_forms_section', 10 );
	vetra_add_textarea_setting( $wp_customize, 'forms_subtitle', 'زیرعنوان صفحه فرم‌ها', 'vetra_forms_section', 20 );
	vetra_add_text_setting( $wp_customize, 'forms_search_placeholder', 'Placeholder جستجو', 'vetra_forms_section', 30 );
	$wp_customize->add_setting( 'vetra_forms_grid_columns', array( 'default' => 3, 'sanitize_callback' => function ( $value ) { return vetra_sanitize_number( $value, 2, 4 ); }, 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_forms_grid_columns', array( 'label' => 'تعداد ستون کارت‌ها', 'section' => 'vetra_forms_section', 'type' => 'select', 'choices' => array( 2 => '۲ ستون', 3 => '۳ ستون', 4 => '۴ ستون' ), 'priority' => 40 ) );
	vetra_add_checkbox_setting( $wp_customize, 'show_form_metadata', 'نمایش متادیتای فرم', 'vetra_forms_section', 50 );
	vetra_add_text_setting( $wp_customize, 'form_button_text', 'متن دکمه فرم', 'vetra_forms_section', 60 );

	$wp_customize->add_section( 'vetra_advanced_section', array( 'title' => 'پیشرفته', 'panel' => 'vetra_portal_panel', 'priority' => 80 ) );
	$wp_customize->add_setting( 'vetra_custom_css', array( 'default' => '', 'sanitize_callback' => 'vetra_sanitize_custom_css', 'transport' => 'refresh' ) );
	if ( class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
		$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'vetra_custom_css', array( 'label' => 'CSS سفارشی', 'description' => 'بدون تگ style. این CSS بعد از استایل قالب اعمال می‌شود.', 'section' => 'vetra_advanced_section', 'code_type' => 'text/css', 'priority' => 10 ) ) );
	} else {
		$wp_customize->add_control( 'vetra_custom_css', array( 'label' => 'CSS سفارشی', 'section' => 'vetra_advanced_section', 'type' => 'textarea', 'priority' => 10 ) );
	}
}
add_action( 'customize_register', 'vetra_customizer_register' );

function vetra_add_text_setting( $customizer, $key, $label, $section, $priority, $sanitize = 'sanitize_text_field', $description = '' ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => $sanitize, 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'description' => $description, 'section' => $section, 'type' => 'text', 'priority' => $priority ) );
}

function vetra_add_textarea_setting( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'sanitize_textarea_field', 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'type' => 'textarea', 'priority' => $priority ) );
}

function vetra_add_color_setting( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'vetra_sanitize_css_value', 'transport' => 'refresh' ) );
	$customizer->add_control( new WP_Customize_Color_Control( $customizer, 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'priority' => $priority ) ) );
}

function vetra_add_number_setting( $customizer, $key, $label, $section, $priority, $min, $max ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => function ( $value ) use ( $min, $max ) { return vetra_sanitize_number( $value, $min, $max ); }, 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'type' => 'number', 'input_attrs' => array( 'min' => $min, 'max' => $max ), 'priority' => $priority ) );
}

function vetra_add_checkbox_setting( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'vetra_sanitize_checkbox', 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'type' => 'checkbox', 'priority' => $priority ) );
}

function vetra_portal_customizer_css() {
	$css = ':root{' .
		'--vetra-bg:' . esc_attr( vetra_option( 'background_color' ) ) . ';' .
		'--vetra-panel:' . esc_attr( vetra_option( 'panel_color' ) ) . ';' .
		'--vetra-gold:' . esc_attr( vetra_option( 'accent_color' ) ) . ';' .
		'--vetra-gold-deep:' . esc_attr( vetra_option( 'accent_deep_color' ) ) . ';' .
		'--vetra-text:' . esc_attr( vetra_option( 'text_color' ) ) . ';' .
		'--vetra-muted:' . esc_attr( vetra_option( 'muted_color' ) ) . ';' .
		'--vetra-line:' . esc_attr( vetra_option( 'line_color' ) ) . ';' .
		'--vetra-font:' . esc_attr( vetra_option( 'font_family' ) ) . ';' .
		'--vetra-radius:' . absint( vetra_option( 'card_radius' ) ) . 'px;' .
		'--vetra-content-width:' . absint( vetra_option( 'content_width' ) ) . 'px;' .
		'--vetra-blur:' . absint( vetra_option( 'glass_blur' ) ) . 'px;' .
		'--vetra-base-font-size:' . absint( vetra_option( 'base_font_size' ) ) . 'px' .
	'}';

	$css .= '.vetra-topbar{position:' . ( vetra_option( 'topbar_sticky' ) ? 'sticky' : 'relative' ) . '}' .
		'.vetra-shell-layout{grid-template-columns:minmax(0,1fr)}' .
		'.vetra-main{width:100%;max-width:var(--vetra-content-width);margin-inline:auto}' .
		'.vetra-form-card{border-radius:var(--vetra-radius)}' .
		'.vetra-forms-grid{grid-template-columns:repeat(' . absint( vetra_option( 'forms_grid_columns' ) ) . ',minmax(0,1fr))}' .
		'.vetra-topbar{backdrop-filter:blur(var(--vetra-blur))}';

	$css .= '@media (max-width:1180px){.vetra-forms-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (max-width:640px){.vetra-forms-grid{grid-template-columns:1fr}}';
	$css .= vetra_option( 'custom_css', '' );
	return wp_strip_all_tags( $css );
}
