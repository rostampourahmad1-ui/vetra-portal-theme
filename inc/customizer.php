<?php
/**
 * Corporate Vetra Customizer.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_customizer_defaults() {
	return array_merge( vetra_settings_defaults(), array(
		'brand_title'       => 'وترا',
		'brand_subtitle'    => 'معماری، مهندسی و ساخت',
	'footer_text'       => 'طراحی دقیق. ساخت ماندگار.',
	'copyright_text'    => '© {year} گروه ساختمانی و مهندسی وترا',
		'header_enabled'    => true,
		'header_cta_enabled' => true,
		'footer_enabled'    => true,
		'footer_contact_enabled' => true,
		'footer_bottom_enabled' => true,
		'home_hero_enabled' => true,
		'home_stats_enabled' => true,
		'home_services_enabled' => true,
		'home_about_enabled' => true,
		'home_projects_enabled' => true,
		'home_cta_enabled'  => true,
		'color_mode'        => 'system',
		'color_palette_enabled' => true,
		'light_bg'          => '#f6f7f4',
		'light_surface'     => '#ffffff',
		'light_text'        => '#171918',
		'light_muted'       => '#66706b',
		'light_accent'      => '#f28b38',
		'light_accent_soft' => '#fff0e3',
		'dark_bg'           => '#111513',
		'dark_surface'      => '#1b211e',
		'dark_text'         => '#f4f4ef',
		'dark_muted'        => '#aab2ad',
		'dark_accent'       => '#ffad57',
		'dark_accent_soft'  => '#34281d',
		'line_color'        => 'rgba(23,25,24,.11)',
		'font_family'       => 'IRANYekan, Vazirmatn, Tahoma, sans-serif',
		'content_width'     => 1320,
		'card_radius'       => 24,
		'header_sticky'     => true,
		'topbar_enabled'    => false,
		'topbar_text'       => 'همراه شما برای ساختن آینده‌ای ماندگار',
		'show_back_to_top'  => true,
		'show_search'       => true,
		'enable_forms_style' => true,
		'enable_bbpress_style' => true,
		'header_cta_text'   => 'شروع همکاری',
		'header_cta_url'    => '#contact',
		'show_theme_switch' => true,
		'hero_eyebrow'      => 'گروه ساختمانی و مهندسی وترا',
		'hero_title'        => 'فضاهایی برای زندگی بهتر می‌سازیم.',
		'hero_description'  => 'وترا در مرز معماری، مهندسی و اجرای دقیق ایستاده است؛ از ایده‌های جسورانه تا فضاهایی که سال‌ها ماندگار می‌مانند.',
		'hero_primary_text' => 'آشنایی با وترا',
		'hero_primary_url'  => '#about',
		'hero_secondary_text' => 'مشاهده پروژه‌ها',
		'hero_secondary_url'  => '#projects',
		'hero_image'        => 0,
		'stat_1_number'     => '۱۲+',
		'stat_1_label'      => 'سال تجربه تخصصی',
		'stat_2_number'     => '۸۰+',
		'stat_2_label'      => 'پروژه تحویل‌شده',
		'stat_3_number'     => '۳.۲M',
		'stat_3_label'      => 'مترمربع طراحی و اجرا',
		'stat_4_number'     => '۲۴',
		'stat_4_label'      => 'استان تحت پوشش',
		'services_title'    => 'تخصصی که به نتیجه تبدیل می‌شود.',
		'services_intro'    => 'یک تیم یکپارچه برای تصمیم‌های بهتر، اجرای دقیق‌تر و ارزش‌آفرینی ماندگار.',
		'service_1_title'   => 'طراحی و معماری',
		'service_1_text'    => 'خلق فضاهای معاصر، انسانی و کارآمد با توجه به زمینه، اقلیم و آینده پروژه.',
		'service_2_title'   => 'مهندسی و مدیریت',
		'service_2_text'    => 'مدیریت یکپارچه زمان، هزینه و کیفیت برای تحویل مطمئن و شفاف پروژه.',
		'service_3_title'   => 'ساخت و توسعه',
		'service_3_text'    => 'تبدیل نقشه به واقعیت با استاندارد اجرایی بالا و توجه جدی به جزئیات.',
		'about_title'       => 'ساختن، فقط بالا بردن دیوارها نیست.',
		'about_text'        => 'ما باور داریم هر پروژه یک اثر ماندگار در شهر است. وترا با ترکیب نگاه معماری، دانش مهندسی و انضباط اجرایی، فضاهایی می‌سازد که کیفیت زندگی و ارزش دارایی را هم‌زمان ارتقا می‌دهند.',
		'about_image'       => 0,
		'projects_title'    => 'پروژه‌هایی که روایت خودشان را دارند.',
		'projects_intro'    => 'منتخبی از مسیر ما در طراحی و ساخت فضاهای متمایز.',
		'project_1_title'   => 'برج سامان',
		'project_1_meta'    => 'تهران · مسکونی · ۱۴۰۳',
		'project_1_image'   => 0,
		'project_2_title'   => 'مجتمع آفتاب',
		'project_2_meta'    => 'کیش · تجاری · ۱۴۰۲',
		'project_2_image'   => 0,
		'project_3_title'   => 'خانه‌ی کوهستان',
		'project_3_meta'    => 'لواسان · ویلایی · ۱۴۰۱',
		'project_3_image'   => 0,
		'cta_title'         => 'پروژه بعدی شما، از یک گفت‌وگو شروع می‌شود.',
		'cta_text'          => 'برای ساختن آینده‌ای دقیق‌تر، با تیم وترا در ارتباط باشید.',
		'cta_button_text'   => 'تماس با ما',
		'cta_button_url'    => '#contact',
		'contact_phone'     => '۰۲۱-۲۲۷۷۰۰۰۰',
		'contact_email'     => 'hello@vetragroup.ir',
		'contact_address'   => 'تهران، خیابان ولیعصر، دفتر مرکزی وترا',
		'custom_css'        => '',

		// Background.
		'background_enabled' => false,
		'bg_apply_mode'     => 'light',
		'bg_type'           => 'gradient',
		'bg_color'          => '#f6f7f4',
		'bg_gradient_start' => '#f6f7f4',
		'bg_gradient_end'   => '#e8e6df',
		'bg_gradient_angle' => 145,
		'bg_image'          => 0,
		'bg_image_size'     => 'cover',
		'bg_image_position' => 'center',
		'bg_image_repeat'   => 'no-repeat',
		'bg_image_attachment' => 'fixed',
		'bg_overlay_color'  => 'rgba(255,255,255,0)',
		'bg_overlay_opacity'=> 0,
		'bg_pattern'        => 'none',

		// Fonts.
		'font_heading'      => 'inherit',
		'font_body'         => 'IRANYekan, Vazirmatn, Tahoma, sans-serif',
		'load_vazirmatn'    => false,
		'load_estedad'      => false,
		'load_dana'         => false,

		// Mobile menu.
		'mobile_menu_style' => 'drawer',
		'mobile_menu_enabled' => true,
		'sticky_bottom_bar' => true,
		'bottom_bar_item_1_icon' => 'home',
		'bottom_bar_item_1_text' => 'خانه',
		'bottom_bar_item_1_url'  => '/',
		'bottom_bar_item_2_icon' => 'services',
		'bottom_bar_item_2_text' => 'خدمات',
		'bottom_bar_item_2_url'  => '#services',
		'bottom_bar_item_3_icon' => 'projects',
		'bottom_bar_item_3_text' => 'پروژه‌ها',
		'bottom_bar_item_3_url'  => '#projects',
		'bottom_bar_item_4_icon' => 'contact',
		'bottom_bar_item_4_text' => 'تماس',
		'bottom_bar_item_4_url'  => '#contact',

		// User bar.
		'show_user_bar'     => false,
		'user_bar_pages'    => array(),
		'show_user_avatar'  => true,
		'show_user_name'    => true,
		'show_user_list'    => false,

		// PWA.
		'pwa_enabled'       => false,
		'pwa_name'          => 'وترا',
		'pwa_short_name'    => 'وترا',
		'pwa_description'   => 'گروه ساختمانی و مهندسی وترا',
		'pwa_theme_color'   => '#f28b38',
		'pwa_bg_color'      => '#f6f7f4',
		'pwa_display'       => 'standalone',
		'pwa_start_url'     => '/',
		'pwa_icon'          => 0,
		'pwa_install_prompt' => true,
		'pwa_install_text'   => 'برای دسترسی سریع‌تر، وب‌اپ وترا را نصب کنید.',
		'pwa_install_delay'  => 5,
	) );
}

function vetra_option( $key, $default = null ) {
	static $operational = null;
	$defaults = vetra_customizer_defaults();
	$fallback = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : $default;
	$value    = get_theme_mod( 'vetra_' . $key, $fallback );
	if ( null === $operational ) { $operational = get_option( 'vetra_operational_settings', array() ); }
	if ( ! is_customize_preview() && is_array( $operational ) && array_key_exists( $key, $operational ) ) {
		$value = $operational[ $key ];
	}
	return ( '' === $value || null === $value ) && null !== $default ? $default : $value;
}

function vetra_sanitize_checkbox( $value ) {
	return (bool) $value;
}

function vetra_sanitize_number( $value, $min = 0, $max = 5000 ) {
	return max( $min, min( $max, absint( $value ) ) );
}

function vetra_sanitize_color( $value ) {
	$value = trim( (string) $value );
	return preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([^)]*\)|hsla?\([^)]*\))$/', $value ) ? $value : '';
}

function vetra_sanitize_mode( $value ) {
	return in_array( $value, array( 'light', 'dark', 'system' ), true ) ? $value : 'system';
}

function vetra_sanitize_bg_apply_mode( $value ) {
	return in_array( $value, array( 'light', 'dark', 'both' ), true ) ? $value : 'light';
}

function vetra_sanitize_bg_type( $value ) {
	return in_array( $value, array( 'color', 'gradient', 'image' ), true ) ? $value : 'color';
}

function vetra_sanitize_bg_size( $value ) {
	return in_array( $value, array( 'cover', 'contain', 'auto' ), true ) ? $value : 'cover';
}

function vetra_sanitize_bg_repeat( $value ) {
	return in_array( $value, array( 'no-repeat', 'repeat', 'repeat-x', 'repeat-y' ), true ) ? $value : 'no-repeat';
}

function vetra_sanitize_bg_attachment( $value ) {
	return in_array( $value, array( 'fixed', 'scroll', 'local' ), true ) ? $value : 'fixed';
}

function vetra_sanitize_pattern( $value ) {
	return in_array( $value, array( 'none', 'dots', 'grid', 'diagonal', 'noise' ), true ) ? $value : 'none';
}

function vetra_sanitize_font_stack( $value ) {
	$allowed = array(
		'IRANYekan, Vazirmatn, Tahoma, sans-serif',
		'Vazirmatn, Tahoma, sans-serif',
		'IRANSans, Tahoma, sans-serif',
		'Estedad, Vazirmatn, Tahoma, sans-serif',
		'Dana, Vazirmatn, Tahoma, sans-serif',
		'YekanBakh, Vazirmatn, Tahoma, sans-serif',
		'Sahel, Vazirmatn, Tahoma, sans-serif',
		'Shabnam, Vazirmatn, Tahoma, sans-serif',
		'Tahoma, Arial, sans-serif',
		'system-ui, sans-serif',
	);
	return in_array( $value, $allowed, true ) ? $value : $allowed[0];
}

function vetra_sanitize_pwa_display( $value ) {
	return in_array( $value, array( 'fullscreen', 'standalone', 'minimal-ui', 'browser' ), true ) ? $value : 'standalone';
}

function vetra_sanitize_pages_array( $value ) {
	if ( is_string( $value ) ) {
		$json = json_decode( $value, true );
		$value = is_array( $json ) ? $json : explode( ',', $value );
	}
	if ( ! is_array( $value ) ) {
		return array();
	}
	return array_map( 'absint', array_filter( $value ) );
}

function vetra_sanitize_custom_css( $value ) {
	return wp_strip_all_tags( (string) $value );
}

function vetra_add_text( $customizer, $key, $label, $section, $priority, $textarea = false ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => $textarea ? 'sanitize_textarea_field' : 'sanitize_text_field', 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'type' => $textarea ? 'textarea' : 'text', 'priority' => $priority ) );
}

function vetra_add_color( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'vetra_sanitize_color', 'transport' => 'refresh' ) );
	$customizer->add_control( new WP_Customize_Color_Control( $customizer, 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'priority' => $priority ) ) );
}

function vetra_add_media( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => 0, 'sanitize_callback' => 'absint', 'transport' => 'refresh' ) );
	$customizer->add_control( new WP_Customize_Media_Control( $customizer, 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'mime_type' => 'image', 'priority' => $priority ) ) );
}

function vetra_add_toggle( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'vetra_sanitize_checkbox', 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'type' => 'checkbox', 'priority' => $priority ) );
}

function vetra_add_select( $customizer, $key, $label, $section, $priority, $choices ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
	$customizer->add_control( 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'type' => 'select', 'choices' => $choices, 'priority' => $priority ) );
}

function vetra_add_pages_multi( $customizer, $key, $label, $section, $priority ) {
	$customizer->add_setting( 'vetra_' . $key, array( 'default' => vetra_customizer_defaults()[ $key ], 'sanitize_callback' => 'vetra_sanitize_pages_array', 'transport' => 'refresh' ) );
	$customizer->add_control( new Vetra_Customize_Control_Checkbox_Multiple( $customizer, 'vetra_' . $key, array( 'label' => $label, 'section' => $section, 'priority' => $priority ) ) );
}

function vetra_customizer_register( $wp_customize ) {
	$wp_customize->add_panel( 'vetra_corporate_panel', array( 'title' => 'هویت شرکتی وترا', 'description' => 'طراحی، محتوا و رنگ‌بندی سایت شرکتی وترا را از یک پنل حرفه‌ای مدیریت کنید.', 'priority' => 25 ) );

	$wp_customize->add_section( 'vetra_identity_section', array( 'title' => 'هویت برند', 'description' => 'نام، شعار و لوگوی اصلی سایت را مدیریت کنید.', 'panel' => 'vetra_corporate_panel', 'priority' => 10 ) );
	vetra_add_text( $wp_customize, 'brand_title', 'نام برند', 'vetra_identity_section', 10 );
	vetra_add_text( $wp_customize, 'brand_subtitle', 'شعار کوتاه برند', 'vetra_identity_section', 20 );
	vetra_add_media( $wp_customize, 'logo', 'لوگوی اصلی', 'vetra_identity_section', 30 );
	vetra_add_media( $wp_customize, 'logo_light', 'لوگوی حالت روشن (اختیاری)', 'vetra_identity_section', 40 );
	vetra_add_media( $wp_customize, 'logo_dark', 'لوگوی حالت تیره (اختیاری)', 'vetra_identity_section', 50 );
	vetra_add_media( $wp_customize, 'custom_icon', 'آیکن اختصاصی (PNG/WebP)', 'vetra_identity_section', 60 );
	$wp_customize->get_section( 'title_tagline' )->panel = 'vetra_corporate_panel';
	$wp_customize->get_section( 'title_tagline' )->priority = 5;

	$wp_customize->add_section( 'vetra_features_section', array( 'title' => 'اجزای فعال قالب', 'description' => 'هر بخش را مستقل فعال یا غیرفعال کنید. غیرفعال‌سازی یک بخش، تنظیمات سایر بخش‌ها را تغییر نمی‌دهد.', 'panel' => 'vetra_corporate_panel', 'priority' => 15 ) );
	vetra_add_toggle( $wp_customize, 'header_enabled', 'فعال‌سازی هدر سایت', 'vetra_features_section', 10 );
	vetra_add_toggle( $wp_customize, 'topbar_enabled', 'نمایش نوار بالایی', 'vetra_features_section', 11 );
	vetra_add_text( $wp_customize, 'topbar_text', 'متن نوار بالایی', 'vetra_features_section', 11 );
	vetra_add_toggle( $wp_customize, 'show_search', 'نمایش جست‌وجوی سایت', 'vetra_features_section', 12 );
	vetra_add_toggle( $wp_customize, 'show_back_to_top', 'نمایش دکمه بازگشت به بالا', 'vetra_features_section', 14 );
	vetra_add_toggle( $wp_customize, 'enable_forms_style', 'استایل‌دهی فرم‌های افزونه‌ها', 'vetra_features_section', 16 );
	vetra_add_toggle( $wp_customize, 'enable_bbpress_style', 'استایل‌دهی bbPress', 'vetra_features_section', 18 );
	vetra_add_toggle( $wp_customize, 'footer_enabled', 'فعال‌سازی فوتر سایت', 'vetra_features_section', 20 );
	vetra_add_toggle( $wp_customize, 'footer_contact_enabled', 'نمایش اطلاعات تماس فوتر', 'vetra_features_section', 25 );
	vetra_add_toggle( $wp_customize, 'footer_bottom_enabled', 'نمایش نوار پایانی فوتر', 'vetra_features_section', 27 );
	vetra_add_toggle( $wp_customize, 'home_hero_enabled', 'نمایش Hero صفحه اصلی', 'vetra_features_section', 30 );
	vetra_add_toggle( $wp_customize, 'home_stats_enabled', 'نمایش نوار آمار', 'vetra_features_section', 40 );
	vetra_add_toggle( $wp_customize, 'home_services_enabled', 'نمایش بخش خدمات', 'vetra_features_section', 50 );
	vetra_add_toggle( $wp_customize, 'home_about_enabled', 'نمایش بخش درباره ما', 'vetra_features_section', 60 );
	vetra_add_toggle( $wp_customize, 'home_projects_enabled', 'نمایش پروژه‌های منتخب صفحه اصلی', 'vetra_features_section', 70 );
	vetra_add_toggle( $wp_customize, 'home_cta_enabled', 'نمایش دعوت به همکاری', 'vetra_features_section', 80 );

	$wp_customize->add_section( 'vetra_theme_section', array( 'title' => 'ظاهر پایه و رنگ‌ها', 'description' => 'این بخش ظاهر اصلی نسخه ۲.۰ را کنترل می‌کند. تنظیمات پس‌زمینه در بخش جداگانه و اختیاری قرار دارد.', 'panel' => 'vetra_corporate_panel', 'priority' => 20 ) );
	$wp_customize->add_setting( 'vetra_color_mode', array( 'default' => 'system', 'sanitize_callback' => 'vetra_sanitize_mode', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_color_mode', array( 'label' => 'حالت رنگی پیش‌فرض', 'section' => 'vetra_theme_section', 'type' => 'select', 'choices' => array( 'system' => 'هماهنگ با دستگاه', 'light' => 'روشن', 'dark' => 'تیره' ), 'priority' => 10 ) );
	vetra_add_toggle( $wp_customize, 'color_palette_enabled', 'فعال‌سازی پالت رنگ سفارشی', 'vetra_theme_section', 15 );
	$wp_customize->add_setting( 'vetra_font_family', array( 'default' => vetra_customizer_defaults()['font_family'], 'sanitize_callback' => 'vetra_sanitize_font_stack', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_font_family', array( 'label' => 'فونت اصلی', 'section' => 'vetra_theme_section', 'type' => 'select', 'choices' => array(
		'IRANYekan, Vazirmatn, Tahoma, sans-serif' => 'IRANYekan',
		'Vazirmatn, Tahoma, sans-serif' => 'Vazirmatn',
		'IRANSans, Tahoma, sans-serif' => 'IRANSans',
		'Estedad, Vazirmatn, Tahoma, sans-serif' => 'Estedad',
		'Dana, Vazirmatn, Tahoma, sans-serif' => 'Dana',
		'YekanBakh, Vazirmatn, Tahoma, sans-serif' => 'Yekan Bakh',
		'Sahel, Vazirmatn, Tahoma, sans-serif' => 'Sahel',
		'Shabnam, Vazirmatn, Tahoma, sans-serif' => 'Shabnam',
		'Tahoma, Arial, sans-serif' => 'Tahoma',
		'system-ui, sans-serif' => 'System UI',
	), 'priority' => 20 ) );
	vetra_add_toggle( $wp_customize, 'load_vazirmatn', 'بارگذاری Vazirmatn از CDN', 'vetra_theme_section', 25 );
	vetra_add_toggle( $wp_customize, 'load_estedad', 'بارگذاری Estedad از CDN', 'vetra_theme_section', 26 );
	vetra_add_toggle( $wp_customize, 'load_dana', 'بارگذاری Dana از CDN', 'vetra_theme_section', 27 );
	vetra_add_color( $wp_customize, 'light_bg', 'پس‌زمینه روشن', 'vetra_theme_section', 30 );
	vetra_add_color( $wp_customize, 'light_surface', 'سطح کارت روشن', 'vetra_theme_section', 40 );
	vetra_add_color( $wp_customize, 'light_text', 'متن روشن', 'vetra_theme_section', 50 );
	vetra_add_color( $wp_customize, 'light_accent', 'رنگ شاخص روشن', 'vetra_theme_section', 60 );
	vetra_add_color( $wp_customize, 'dark_bg', 'پس‌زمینه تیره', 'vetra_theme_section', 70 );
	vetra_add_color( $wp_customize, 'dark_surface', 'سطح کارت تیره', 'vetra_theme_section', 80 );
	vetra_add_color( $wp_customize, 'dark_text', 'متن تیره', 'vetra_theme_section', 90 );
	vetra_add_color( $wp_customize, 'dark_accent', 'رنگ شاخص تیره', 'vetra_theme_section', 100 );

	$wp_customize->add_section( 'vetra_background_section', array( 'title' => 'پس‌زمینه جامع (اختیاری)', 'description' => 'تا زمانی که گزینه فعال‌سازی روشن نباشد، این بخش هیچ تغییری در ظاهر نسخه ۲.۰ ایجاد نمی‌کند.', 'panel' => 'vetra_corporate_panel', 'priority' => 22 ) );
	vetra_add_toggle( $wp_customize, 'background_enabled', 'فعال‌سازی پس‌زمینه سفارشی', 'vetra_background_section', 5 );
	$wp_customize->add_setting( 'vetra_bg_apply_mode', array( 'default' => 'light', 'sanitize_callback' => 'vetra_sanitize_bg_apply_mode', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_bg_apply_mode', array( 'label' => 'محدوده اعمال پس‌زمینه', 'section' => 'vetra_background_section', 'type' => 'select', 'choices' => array( 'light' => 'فقط حالت روشن', 'dark' => 'فقط حالت تیره', 'both' => 'هر دو حالت' ), 'priority' => 7 ) );
	vetra_add_select( $wp_customize, 'bg_type', 'نوع پس‌زمینه', 'vetra_background_section', 10, array( 'color' => 'رنگ ساده', 'gradient' => 'گرادیان', 'image' => 'تصویر' ) );
	vetra_add_color( $wp_customize, 'bg_color', 'رنگ پس‌زمینه', 'vetra_background_section', 20 );
	vetra_add_color( $wp_customize, 'bg_gradient_start', 'رنگ شروع گرادیان', 'vetra_background_section', 30 );
	vetra_add_color( $wp_customize, 'bg_gradient_end', 'رنگ پایان گرادیان', 'vetra_background_section', 40 );
	$wp_customize->add_setting( 'vetra_bg_gradient_angle', array( 'default' => 145, 'sanitize_callback' => function( $v ) { return vetra_sanitize_number( $v, 0, 360 ); }, 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_bg_gradient_angle', array( 'label' => 'زاویه گرادیان (درجه)', 'section' => 'vetra_background_section', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 360 ), 'priority' => 50 ) );
	vetra_add_media( $wp_customize, 'bg_image', 'تصویر پس‌زمینه', 'vetra_background_section', 60 );
	vetra_add_select( $wp_customize, 'bg_image_size', 'اندازه تصویر', 'vetra_background_section', 70, array( 'cover' => 'پوشش کامل', 'contain' => 'جای‌گیری', 'auto' => 'خودکار' ) );
	vetra_add_select( $wp_customize, 'bg_image_position', 'موقعیت تصویر', 'vetra_background_section', 80, array( 'center' => 'مرکز', 'top' => 'بالا', 'bottom' => 'پایین', 'left' => 'چپ', 'right' => 'راست', 'top left' => 'بالا چپ', 'top right' => 'بالا راست', 'bottom left' => 'پایین چپ', 'bottom right' => 'پایین راست' ) );
	vetra_add_select( $wp_customize, 'bg_image_repeat', 'تکرار تصویر', 'vetra_background_section', 90, array( 'no-repeat' => 'بدون تکرار', 'repeat' => 'تکرار', 'repeat-x' => 'تکرار افقی', 'repeat-y' => 'تکرار عمودی' ) );
	vetra_add_select( $wp_customize, 'bg_image_attachment', 'چسبندگی تصویر', 'vetra_background_section', 100, array( 'fixed' => 'ثابت', 'scroll' => 'اسکرول', 'local' => 'محلی' ) );
	vetra_add_select( $wp_customize, 'bg_pattern', 'الگوی رویه', 'vetra_background_section', 110, array( 'none' => 'بدون الگو', 'dots' => 'نقطه‌ای', 'grid' => 'شبکه', 'diagonal' => 'مورب', 'noise' => 'نویز' ) );
	vetra_add_color( $wp_customize, 'bg_overlay_color', 'رنگ Overlay', 'vetra_background_section', 120 );
	$wp_customize->add_setting( 'vetra_bg_overlay_opacity', array( 'default' => 0, 'sanitize_callback' => function( $v ) { return vetra_sanitize_number( $v, 0, 100 ); }, 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_bg_overlay_opacity', array( 'label' => 'شفافیت Overlay (٪)', 'section' => 'vetra_background_section', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 100 ), 'priority' => 130 ) );

	$wp_customize->add_section( 'vetra_header_section', array( 'title' => 'هدر و تماس', 'description' => 'نمایش هدر، منوی اصلی، دکمه تماس و حالت رنگی را کنترل کنید.', 'panel' => 'vetra_corporate_panel', 'priority' => 30 ) );
	vetra_add_toggle( $wp_customize, 'header_sticky', 'هدر چسبان', 'vetra_header_section', 10 );
	vetra_add_toggle( $wp_customize, 'show_theme_switch', 'نمایش کلید حالت روشن/تیره', 'vetra_header_section', 20 );
	vetra_add_toggle( $wp_customize, 'header_cta_enabled', 'نمایش دکمه همکاری هدر', 'vetra_header_section', 25 );
	vetra_add_text( $wp_customize, 'header_cta_text', 'متن دکمه هدر', 'vetra_header_section', 30 );
	vetra_add_text( $wp_customize, 'header_cta_url', 'لینک دکمه هدر', 'vetra_header_section', 40 );

	$wp_customize->add_section( 'vetra_mobile_menu_section', array( 'title' => 'منوی موبایل', 'description' => 'منوی موبایل و نوار دسترسی سریع پایین صفحه را مستقل مدیریت کنید.', 'panel' => 'vetra_corporate_panel', 'priority' => 35 ) );
	vetra_add_toggle( $wp_customize, 'mobile_menu_enabled', 'فعال‌سازی منوی موبایل', 'vetra_mobile_menu_section', 5 );
	vetra_add_select( $wp_customize, 'mobile_menu_style', 'استایل منوی موبایل', 'vetra_mobile_menu_section', 10, array( 'drawer' => 'کشویی از بالا', 'bottom-sheet' => 'برگه پایین' ) );
	vetra_add_toggle( $wp_customize, 'sticky_bottom_bar', 'منوی چسبان پایین موبایل', 'vetra_mobile_menu_section', 20 );
	for ( $i = 1; $i <= 4; $i++ ) {
		vetra_add_text( $wp_customize, 'bottom_bar_item_' . $i . '_text', 'برچسب آیتم ' . $i, 'vetra_mobile_menu_section', 30 + ( $i * 10 ) );
		vetra_add_text( $wp_customize, 'bottom_bar_item_' . $i . '_url', 'لینک آیتم ' . $i, 'vetra_mobile_menu_section', 35 + ( $i * 10 ) );
		vetra_add_text( $wp_customize, 'bottom_bar_item_' . $i . '_icon', 'کلاس آیکون آیتم ' . $i, 'vetra_mobile_menu_section', 38 + ( $i * 10 ) );
	}

	$wp_customize->add_section( 'vetra_user_bar_section', array( 'title' => 'نوار کاربر', 'panel' => 'vetra_corporate_panel', 'priority' => 37 ) );
	vetra_add_toggle( $wp_customize, 'show_user_bar', 'نمایش نوار کاربر', 'vetra_user_bar_section', 10 );
	vetra_add_pages_multi( $wp_customize, 'user_bar_pages', 'صفحات نمایش نوار کاربر', 'vetra_user_bar_section', 20 );
	vetra_add_toggle( $wp_customize, 'show_user_avatar', 'نمایش آواتار کاربر جاری', 'vetra_user_bar_section', 30 );
	vetra_add_toggle( $wp_customize, 'show_user_name', 'نمایش نام کاربر جاری', 'vetra_user_bar_section', 40 );
	vetra_add_toggle( $wp_customize, 'show_user_list', 'نمایش فهرست کاربران', 'vetra_user_bar_section', 50 );

	$wp_customize->add_section( 'vetra_hero_section', array( 'title' => 'Hero صفحه اصلی', 'panel' => 'vetra_corporate_panel', 'priority' => 40 ) );
	vetra_add_text( $wp_customize, 'hero_eyebrow', 'برچسب بالای عنوان', 'vetra_hero_section', 10 );
	vetra_add_text( $wp_customize, 'hero_title', 'عنوان اصلی', 'vetra_hero_section', 20, true );
	vetra_add_text( $wp_customize, 'hero_description', 'توضیح Hero', 'vetra_hero_section', 30, true );
	vetra_add_text( $wp_customize, 'hero_primary_text', 'متن دکمه اصلی', 'vetra_hero_section', 40 );
	vetra_add_text( $wp_customize, 'hero_primary_url', 'لینک دکمه اصلی', 'vetra_hero_section', 50 );
	vetra_add_text( $wp_customize, 'hero_secondary_text', 'متن دکمه دوم', 'vetra_hero_section', 60 );
	vetra_add_text( $wp_customize, 'hero_secondary_url', 'لینک دکمه دوم', 'vetra_hero_section', 70 );
	vetra_add_media( $wp_customize, 'hero_image', 'تصویر Hero', 'vetra_hero_section', 80 );

	$wp_customize->add_section( 'vetra_content_section', array( 'title' => 'محتوای شرکتی', 'panel' => 'vetra_corporate_panel', 'priority' => 50 ) );
	vetra_add_text( $wp_customize, 'services_title', 'عنوان خدمات', 'vetra_content_section', 10 );
	vetra_add_text( $wp_customize, 'services_intro', 'توضیح خدمات', 'vetra_content_section', 20, true );
	foreach ( array( 1, 2, 3 ) as $index ) {
		vetra_add_text( $wp_customize, 'service_' . $index . '_title', 'عنوان خدمت ' . $index, 'vetra_content_section', 20 + ( $index * 10 ) );
		vetra_add_text( $wp_customize, 'service_' . $index . '_text', 'توضیح خدمت ' . $index, 'vetra_content_section', 25 + ( $index * 10 ), true );
	}
	vetra_add_text( $wp_customize, 'about_title', 'عنوان درباره وترا', 'vetra_content_section', 70 );
	vetra_add_text( $wp_customize, 'about_text', 'متن درباره وترا', 'vetra_content_section', 80, true );
	vetra_add_media( $wp_customize, 'about_image', 'تصویر درباره وترا', 'vetra_content_section', 90 );
	vetra_add_text( $wp_customize, 'projects_title', 'عنوان پروژه‌ها', 'vetra_content_section', 100 );
	vetra_add_text( $wp_customize, 'projects_intro', 'توضیح پروژه‌ها', 'vetra_content_section', 110, true );
	foreach ( array( 1, 2, 3 ) as $index ) {
		vetra_add_text( $wp_customize, 'project_' . $index . '_title', 'عنوان پروژه ' . $index, 'vetra_content_section', 110 + ( $index * 10 ) );
		vetra_add_text( $wp_customize, 'project_' . $index . '_meta', 'اطلاعات پروژه ' . $index, 'vetra_content_section', 115 + ( $index * 10 ) );
		vetra_add_media( $wp_customize, 'project_' . $index . '_image', 'تصویر پروژه ' . $index, 'vetra_content_section', 118 + ( $index * 10 ) );
	}

	$wp_customize->add_section( 'vetra_contact_section', array( 'title' => 'تماس و CTA', 'panel' => 'vetra_corporate_panel', 'priority' => 60 ) );
	vetra_add_text( $wp_customize, 'footer_text', 'متن معرفی فوتر', 'vetra_contact_section', 5, true );
	vetra_add_text( $wp_customize, 'copyright_text', 'متن حق‌نشر ({year} برای سال جاری)', 'vetra_contact_section', 6 );
	vetra_add_text( $wp_customize, 'cta_title', 'عنوان دعوت به همکاری', 'vetra_contact_section', 10 );
	vetra_add_text( $wp_customize, 'cta_text', 'توضیح دعوت به همکاری', 'vetra_contact_section', 20, true );
	vetra_add_text( $wp_customize, 'cta_button_text', 'متن دکمه CTA', 'vetra_contact_section', 30 );
	vetra_add_text( $wp_customize, 'cta_button_url', 'لینک دکمه CTA', 'vetra_contact_section', 40 );
	vetra_add_text( $wp_customize, 'contact_phone', 'تلفن', 'vetra_contact_section', 50 );
	vetra_add_text( $wp_customize, 'contact_email', 'ایمیل', 'vetra_contact_section', 60 );
	vetra_add_text( $wp_customize, 'contact_address', 'آدرس', 'vetra_contact_section', 70 );

	$wp_customize->add_section( 'vetra_pwa_section', array( 'title' => 'وب‌اپ PWA', 'panel' => 'vetra_corporate_panel', 'priority' => 70 ) );
	vetra_add_toggle( $wp_customize, 'pwa_enabled', 'فعال‌سازی PWA', 'vetra_pwa_section', 10 );
	vetra_add_text( $wp_customize, 'pwa_name', 'نام اپ', 'vetra_pwa_section', 20 );
	vetra_add_text( $wp_customize, 'pwa_short_name', 'نام کوتاه', 'vetra_pwa_section', 30 );
	vetra_add_text( $wp_customize, 'pwa_description', 'توضیح اپ', 'vetra_pwa_section', 40 );
	vetra_add_color( $wp_customize, 'pwa_theme_color', 'رنگ تم', 'vetra_pwa_section', 50 );
	vetra_add_color( $wp_customize, 'pwa_bg_color', 'رنگ پس‌زمینه اپ', 'vetra_pwa_section', 60 );
	vetra_add_select( $wp_customize, 'pwa_display', 'حالت نمایش', 'vetra_pwa_section', 70, array( 'fullscreen' => 'تمام‌صفحه', 'standalone' => 'standalone', 'minimal-ui' => 'minimal-ui', 'browser' => 'browser' ) );
	vetra_add_text( $wp_customize, 'pwa_start_url', 'آدرس شروع', 'vetra_pwa_section', 80 );
	vetra_add_media( $wp_customize, 'pwa_icon', 'آیکون PWA (مربع ۵۱۲px)', 'vetra_pwa_section', 90 );
	vetra_add_toggle( $wp_customize, 'pwa_install_prompt', 'نمایش پیشنهاد نصب', 'vetra_pwa_section', 100 );
	vetra_add_text( $wp_customize, 'pwa_install_text', 'متن پیشنهاد نصب', 'vetra_pwa_section', 110 );
	$wp_customize->add_setting( 'vetra_pwa_install_delay', array( 'default' => 5, 'sanitize_callback' => function( $v ) { return vetra_sanitize_number( $v, 0, 86400 ); } ) );
	$wp_customize->add_control( 'vetra_pwa_install_delay', array( 'label' => 'تأخیر نمایش پیشنهاد (ثانیه)', 'section' => 'vetra_pwa_section', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 86400 ), 'priority' => 120 ) );

	$wp_customize->add_section( 'vetra_advanced_section', array( 'title' => 'پیشرفته', 'panel' => 'vetra_corporate_panel', 'priority' => 80 ) );
	$wp_customize->add_setting( 'vetra_custom_css', array( 'default' => '', 'sanitize_callback' => 'vetra_sanitize_custom_css', 'transport' => 'refresh' ) );
	if ( class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
		$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'vetra_custom_css', array( 'label' => 'CSS سفارشی', 'description' => 'بدون تگ style. این CSS بعد از استایل قالب اعمال می‌شود.', 'section' => 'vetra_advanced_section', 'code_type' => 'text/css', 'priority' => 10 ) ) );
	} else {
		$wp_customize->add_control( 'vetra_custom_css', array( 'label' => 'CSS سفارشی', 'section' => 'vetra_advanced_section', 'type' => 'textarea', 'priority' => 10 ) );
	}
}
add_action( 'customize_register', 'vetra_customizer_register' );

/**
 * Multiple checkbox control for pages selection.
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class Vetra_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {
		public $type = 'checkbox_multiple';

		public function render_content() {
			if ( empty( $this->choices ) ) {
				$pages = get_pages( array( 'number' => 100 ) );
				foreach ( $pages as $page ) {
					$this->choices[ $page->ID ] = $page->post_title;
				}
			}
			$value = is_array( $this->value() ) ? $this->value() : array();
			?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><?php endif; ?>
			</label>
			<ul style="max-height:200px;overflow:auto;border:1px solid #ddd;padding:8px 12px;background:#fff">
			<?php foreach ( $this->choices as $id => $label ) : ?>
				<li><label><input type="checkbox" value="<?php echo esc_attr( $id ); ?>" <?php checked( in_array( (string) $id, array_map( 'strval', $value ), true ) ); ?> class="vetra-multi-checkbox" /> <?php echo esc_html( $label ); ?></label></li>
			<?php endforeach; ?>
			</ul>
			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $value ) ); ?>" />
			<?php
		}
	}
}

function vetra_customizer_css() {
	$cache_key = 'vetra_dynamic_css_' . md5( wp_json_encode( vetra_theme_css_fingerprint() ) );
	if ( ! is_customize_preview() ) {
		$cached_css = get_transient( $cache_key );
		if ( false !== $cached_css ) { return (string) $cached_css; }
	}
	$defaults = vetra_customizer_defaults();
	$palette_enabled = (bool) vetra_option( 'color_palette_enabled', true );
	$light_bg = $palette_enabled ? vetra_option( 'light_bg' ) : $defaults['light_bg'];
	$light_surface = $palette_enabled ? vetra_option( 'light_surface' ) : $defaults['light_surface'];
	$light_text = $palette_enabled ? vetra_option( 'light_text' ) : $defaults['light_text'];
	$light_muted = $palette_enabled ? vetra_option( 'light_muted' ) : $defaults['light_muted'];
	$light_accent = $palette_enabled ? vetra_option( 'light_accent' ) : $defaults['light_accent'];
	$light_accent_soft = $palette_enabled ? vetra_option( 'light_accent_soft' ) : $defaults['light_accent_soft'];
	$dark_bg = $palette_enabled ? vetra_option( 'dark_bg' ) : $defaults['dark_bg'];
	$dark_surface = $palette_enabled ? vetra_option( 'dark_surface' ) : $defaults['dark_surface'];
	$dark_text = $palette_enabled ? vetra_option( 'dark_text' ) : $defaults['dark_text'];
	$dark_muted = $palette_enabled ? vetra_option( 'dark_muted' ) : $defaults['dark_muted'];
	$dark_accent = $palette_enabled ? vetra_option( 'dark_accent' ) : $defaults['dark_accent'];
	$dark_accent_soft = $palette_enabled ? vetra_option( 'dark_accent_soft' ) : $defaults['dark_accent_soft'];

	// Background builder.
	$bg_css = '';
	$bg_type = vetra_option( 'bg_type', 'gradient' );
	if ( 'image' === $bg_type ) {
		$image = vetra_image_url( 'bg_image' );
		if ( $image ) {
			$bg_css = 'background-image:url(' . esc_url( $image ) . ');background-size:' . esc_attr( vetra_option( 'bg_image_size' ) ) . ';background-position:' . esc_attr( vetra_option( 'bg_image_position' ) ) . ';background-repeat:' . esc_attr( vetra_option( 'bg_image_repeat' ) ) . ';background-attachment:' . esc_attr( vetra_option( 'bg_image_attachment' ) ) . ';';
		} else {
			$bg_css = 'background:' . esc_attr( $light_bg ) . ';';
		}
	} elseif ( 'gradient' === $bg_type ) {
		$bg_css = 'background:linear-gradient(' . absint( vetra_option( 'bg_gradient_angle' ) ) . 'deg,' . esc_attr( vetra_option( 'bg_gradient_start' ) ) . ',' . esc_attr( vetra_option( 'bg_gradient_end' ) ) . ');';
	} else {
		$bg_css = 'background:' . esc_attr( vetra_option( 'bg_color' ) ) . ';';
	}

	$pattern = vetra_option( 'bg_pattern', 'none' );
	$pattern_css = '';
	if ( 'dots' === $pattern ) {
		$pattern_css = 'background-image:radial-gradient(circle,currentColor 1px,transparent 1px);background-size:24px 24px;opacity:.06;';
	} elseif ( 'grid' === $pattern ) {
		$pattern_css = 'background-image:linear-gradient(currentColor 1px,transparent 1px),linear-gradient(90deg,currentColor 1px,transparent 1px);background-size:40px 40px;opacity:.04;';
	} elseif ( 'diagonal' === $pattern ) {
		$pattern_css = 'background-image:repeating-linear-gradient(45deg,currentColor 0,currentColor 1px,transparent 0,transparent 50%);background-size:20px 20px;opacity:.04;';
	} elseif ( 'noise' === $pattern ) {
		$pattern_css = 'background-image:url("data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'n\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23n)\' opacity=\'0.4\'/%3E%3C/svg%3E");opacity:.05;';
	}

	$root     = ':root{--vetra-font:' . esc_attr( vetra_option( 'font_family' ) ) . ';--vetra-radius:' . absint( vetra_option( 'card_radius', 24 ) ) . 'px;--vetra-content-width:' . absint( vetra_option( 'content_width', 1320 ) ) . 'px;--vetra-base-size:16px;--vetra-bg:' . esc_attr( $light_bg ) . ';--vetra-surface:' . esc_attr( $light_surface ) . ';--vetra-text:' . esc_attr( $light_text ) . ';--vetra-muted:' . esc_attr( $light_muted ) . ';--vetra-accent:' . esc_attr( $light_accent ) . ';--vetra-accent-soft:' . esc_attr( $light_accent_soft ) . ';--vetra-line:' . esc_attr( vetra_option( 'line_color' ) ) . '}';
	$dark     = 'html[data-theme="dark"]{--vetra-bg:' . esc_attr( $dark_bg ) . ';--vetra-surface:' . esc_attr( $dark_surface ) . ';--vetra-text:' . esc_attr( $dark_text ) . ';--vetra-muted:' . esc_attr( $dark_muted ) . ';--vetra-accent:' . esc_attr( $dark_accent ) . ';--vetra-accent-soft:' . esc_attr( $dark_accent_soft ) . '}';
	$layout   = '.vetra-main{max-width:var(--vetra-content-width);margin-inline:auto}.vetra-topbar{position:' . ( vetra_option( 'header_sticky' ) ? 'sticky' : 'relative' ) . '}.vetra-site-shell{position:relative;background:var(--vetra-bg)}';
	$background_enabled = (bool) vetra_option( 'background_enabled', false );
	$background_rule = '';
	$pattern_rule = '';
	if ( $background_enabled ) {
		$overlay_opacity = absint( vetra_option( 'bg_overlay_opacity', 0 ) );
		$overlay_color = vetra_option( 'bg_overlay_color', 'rgba(255,255,255,0)' );
		if ( $overlay_opacity > 0 && 'image' !== $bg_type ) {
			$overlay_layer = 'linear-gradient(color-mix(in srgb,' . esc_attr( $overlay_color ) . ' ' . $overlay_opacity . '%,transparent),color-mix(in srgb,' . esc_attr( $overlay_color ) . ' ' . $overlay_opacity . '%,transparent)),';
			$bg_css = preg_replace( '/^background:/', 'background:' . $overlay_layer, $bg_css );
		}
		$apply_mode = vetra_option( 'bg_apply_mode', 'light' );
		$selector = 'body.vetra-bg-active .vetra-site-shell';
		if ( 'light' === $apply_mode ) {
			$selector = 'html:not([data-theme="dark"]) ' . $selector;
		} elseif ( 'dark' === $apply_mode ) {
			$selector = 'html[data-theme="dark"] ' . $selector;
		}
		$background_rule = $selector . '{' . $bg_css . '}';
		$pattern_rule = $pattern_css ? $selector . '::after{position:absolute;z-index:0;inset:0;pointer-events:none;content:"";' . $pattern_css . '}' : '';
	}

	$css = $root . $dark . $layout . $background_rule . $pattern_rule . vetra_option( 'custom_css', '' );
	if ( ! is_customize_preview() ) { set_transient( $cache_key, $css, WEEK_IN_SECONDS ); }
	return $css;
}

function vetra_portal_customizer_css() {
	return vetra_customizer_css();
}

function vetra_customizer_controls_assets() {
	wp_enqueue_style( 'vetra-customizer-controls', VETRA_PORTAL_URI . '/assets/css/customizer.css', array(), VETRA_PORTAL_VERSION );
	wp_enqueue_script( 'vetra-customizer-controls', VETRA_PORTAL_URI . '/assets/js/customizer-controls.js', array( 'jquery', 'customize-controls' ), VETRA_PORTAL_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'vetra_customizer_controls_assets' );

/**
 * PWA manifest endpoint.
 */
function vetra_pwa_add_rewrite() {
	add_rewrite_endpoint( 'vetra-manifest', EP_ROOT );
	add_rewrite_endpoint( 'vetra-sw', EP_ROOT );
}
add_action( 'init', 'vetra_pwa_add_rewrite' );

function vetra_pwa_manifest_template() {
	global $wp_query;
	if ( isset( $wp_query->query_vars['vetra-sw'] ) ) {
		if ( ! vetra_option( 'pwa_enabled' ) ) { status_header( 404 ); exit; }
		$worker = VETRA_PORTAL_DIR . '/assets/js/service-worker.js';
		if ( ! is_readable( $worker ) ) { status_header( 404 ); exit; }
		header( 'Content-Type: application/javascript; charset=' . get_bloginfo( 'charset' ) );
		header( 'Service-Worker-Allowed: /' );
		header( 'Cache-Control: no-cache, must-revalidate' );
		readfile( $worker ); exit;
	}
	if ( ! isset( $wp_query->query_vars['vetra-manifest'] ) ) {
		return;
	}
	if ( ! vetra_option( 'pwa_enabled' ) ) {
		wp_die( esc_html__( 'PWA غیرفعال است.', 'vetra-portal' ), esc_html__( 'PWA', 'vetra-portal' ), array( 'response' => 404 ) );
	}

	$icon_url = vetra_image_url( 'pwa_icon' );
	if ( ! $icon_url ) {
		$icon_url = get_site_icon_url( 512, '' );
	}
	if ( ! $icon_url ) {
		$icon_url = VETRA_PORTAL_URI . '/assets/images/favicon.png';
	}
	$icons = array();
	if ( $icon_url ) {
		$icons[] = array( 'src' => $icon_url, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable' );
		$icons[] = array( 'src' => $icon_url, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable' );
	}

	$manifest = array(
		'name'            => vetra_option( 'pwa_name', 'وترا' ),
		'short_name'      => vetra_option( 'pwa_short_name', 'وترا' ),
		'description'     => vetra_option( 'pwa_description', 'گروه ساختمانی و مهندسی وترا' ),
		'start_url'       => vetra_option( 'pwa_start_url', '/' ),
		'display'         => vetra_option( 'pwa_display', 'standalone' ),
		'background_color'=> vetra_option( 'pwa_bg_color', '#f6f7f4' ),
		'theme_color'     => vetra_option( 'pwa_theme_color', '#f28b38' ),
		'orientation'     => 'portrait',
			'lang'            => determine_locale(),
			'dir'             => is_rtl() ? 'rtl' : 'ltr',
	);
	if ( $icons ) {
		$manifest['icons'] = $icons;
	}

	header( 'Content-Type: application/manifest+json; charset=' . get_bloginfo( 'charset' ) );
	echo wp_json_encode( $manifest, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	exit;
}
add_action( 'template_redirect', 'vetra_pwa_manifest_template' );

function vetra_pwa_manifest_link() {
	if ( ! vetra_option( 'pwa_enabled' ) ) {
		return;
	}
	echo '<link rel="manifest" href="' . esc_url( home_url( '/vetra-manifest' ) ) . '">' . "\n";
	echo '<meta name="theme-color" content="' . esc_attr( vetra_option( 'pwa_theme_color', '#f28b38' ) ) . '">' . "\n";
	echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="apple-mobile-web-app-status-bar-style" content="default">' . "\n";
	echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( vetra_option( 'pwa_short_name', 'وترا' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'vetra_pwa_manifest_link', 1 );
