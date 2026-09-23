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
	return array(
		'brand_title'       => 'وترا',
		'brand_subtitle'    => 'معماری، مهندسی و ساخت',
		'footer_text'       => 'طراحی دقیق. ساخت ماندگار.',
		'color_mode'        => 'system',
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
	return max( $min, min( $max, absint( $value ) ) );
}

function vetra_sanitize_color( $value ) {
	$value = trim( (string) $value );
	return preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([^)]*\)|hsla?\([^)]*\))$/', $value ) ? $value : '';
}

function vetra_sanitize_mode( $value ) {
	return in_array( $value, array( 'light', 'dark', 'system' ), true ) ? $value : 'system';
}

function vetra_sanitize_font( $value ) {
	$allowed = array( 'IRANYekan, Vazirmatn, Tahoma, sans-serif', 'Vazirmatn, Tahoma, sans-serif', 'IRANSans, Tahoma, sans-serif', 'Tahoma, Arial, sans-serif', 'system-ui, sans-serif' );
	return in_array( $value, $allowed, true ) ? $value : $allowed[0];
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

function vetra_customizer_register( $wp_customize ) {
	$wp_customize->add_panel( 'vetra_corporate_panel', array( 'title' => 'هویت شرکتی وترا', 'description' => 'طراحی، محتوا و رنگ‌بندی سایت شرکتی وترا را از یک پنل حرفه‌ای مدیریت کنید.', 'priority' => 25 ) );

	$wp_customize->add_section( 'vetra_identity_section', array( 'title' => 'هویت برند', 'panel' => 'vetra_corporate_panel', 'priority' => 10 ) );
	vetra_add_text( $wp_customize, 'brand_title', 'نام برند', 'vetra_identity_section', 10 );
	vetra_add_text( $wp_customize, 'brand_subtitle', 'شعار کوتاه برند', 'vetra_identity_section', 20 );
	vetra_add_media( $wp_customize, 'logo', 'لوگوی اصلی', 'vetra_identity_section', 30 );

	$wp_customize->add_section( 'vetra_theme_section', array( 'title' => 'حالت نمایش و رنگ‌ها', 'panel' => 'vetra_corporate_panel', 'priority' => 20 ) );
	$wp_customize->add_setting( 'vetra_color_mode', array( 'default' => 'system', 'sanitize_callback' => 'vetra_sanitize_mode', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_color_mode', array( 'label' => 'حالت رنگی پیش‌فرض', 'section' => 'vetra_theme_section', 'type' => 'select', 'choices' => array( 'system' => 'هماهنگ با دستگاه', 'light' => 'روشن', 'dark' => 'تیره' ), 'priority' => 10 ) );
	$wp_customize->add_setting( 'vetra_font_family', array( 'default' => vetra_customizer_defaults()['font_family'], 'sanitize_callback' => 'vetra_sanitize_font', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'vetra_font_family', array( 'label' => 'فونت اصلی', 'section' => 'vetra_theme_section', 'type' => 'select', 'choices' => array( 'IRANYekan, Vazirmatn, Tahoma, sans-serif' => 'IRANYekan / Vazirmatn', 'Vazirmatn, Tahoma, sans-serif' => 'Vazirmatn', 'IRANSans, Tahoma, sans-serif' => 'IRANSans', 'Tahoma, Arial, sans-serif' => 'Tahoma', 'system-ui, sans-serif' => 'System UI' ), 'priority' => 20 ) );
	vetra_add_color( $wp_customize, 'light_bg', 'پس‌زمینه روشن', 'vetra_theme_section', 30 );
	vetra_add_color( $wp_customize, 'light_surface', 'سطح کارت روشن', 'vetra_theme_section', 40 );
	vetra_add_color( $wp_customize, 'light_text', 'متن روشن', 'vetra_theme_section', 50 );
	vetra_add_color( $wp_customize, 'light_accent', 'رنگ شاخص روشن', 'vetra_theme_section', 60 );
	vetra_add_color( $wp_customize, 'dark_bg', 'پس‌زمینه تیره', 'vetra_theme_section', 70 );
	vetra_add_color( $wp_customize, 'dark_surface', 'سطح کارت تیره', 'vetra_theme_section', 80 );
	vetra_add_color( $wp_customize, 'dark_text', 'متن تیره', 'vetra_theme_section', 90 );
	vetra_add_color( $wp_customize, 'dark_accent', 'رنگ شاخص تیره', 'vetra_theme_section', 100 );

	$wp_customize->add_section( 'vetra_header_section', array( 'title' => 'هدر و تماس', 'panel' => 'vetra_corporate_panel', 'priority' => 30 ) );
	vetra_add_toggle( $wp_customize, 'header_sticky', 'هدر چسبان', 'vetra_header_section', 10 );
	vetra_add_toggle( $wp_customize, 'show_theme_switch', 'نمایش کلید حالت روشن/تیره', 'vetra_header_section', 20 );
	vetra_add_text( $wp_customize, 'header_cta_text', 'متن دکمه هدر', 'vetra_header_section', 30 );
	vetra_add_text( $wp_customize, 'header_cta_url', 'لینک دکمه هدر', 'vetra_header_section', 40 );

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
	vetra_add_text( $wp_customize, 'cta_title', 'عنوان دعوت به همکاری', 'vetra_contact_section', 10 );
	vetra_add_text( $wp_customize, 'cta_text', 'توضیح دعوت به همکاری', 'vetra_contact_section', 20, true );
	vetra_add_text( $wp_customize, 'cta_button_text', 'متن دکمه CTA', 'vetra_contact_section', 30 );
	vetra_add_text( $wp_customize, 'cta_button_url', 'لینک دکمه CTA', 'vetra_contact_section', 40 );
	vetra_add_text( $wp_customize, 'contact_phone', 'تلفن', 'vetra_contact_section', 50 );
	vetra_add_text( $wp_customize, 'contact_email', 'ایمیل', 'vetra_contact_section', 60 );
	vetra_add_text( $wp_customize, 'contact_address', 'آدرس', 'vetra_contact_section', 70 );

	$wp_customize->add_section( 'vetra_advanced_section', array( 'title' => 'پیشرفته', 'panel' => 'vetra_corporate_panel', 'priority' => 70 ) );
	$wp_customize->add_setting( 'vetra_custom_css', array( 'default' => '', 'sanitize_callback' => 'vetra_sanitize_custom_css', 'transport' => 'refresh' ) );
	if ( class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
		$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'vetra_custom_css', array( 'label' => 'CSS سفارشی', 'description' => 'بدون تگ style. این CSS بعد از استایل قالب اعمال می‌شود.', 'section' => 'vetra_advanced_section', 'code_type' => 'text/css', 'priority' => 10 ) ) );
	} else {
		$wp_customize->add_control( 'vetra_custom_css', array( 'label' => 'CSS سفارشی', 'section' => 'vetra_advanced_section', 'type' => 'textarea', 'priority' => 10 ) );
	}
}
add_action( 'customize_register', 'vetra_customizer_register' );

function vetra_customizer_css() {
	$defaults = vetra_customizer_defaults();
	$root     = ':root{--vetra-font:' . esc_attr( vetra_option( 'font_family' ) ) . ';--vetra-radius:' . absint( vetra_option( 'card_radius', 24 ) ) . 'px;--vetra-content-width:' . absint( vetra_option( 'content_width', 1320 ) ) . 'px;--vetra-base-size:16px;--vetra-bg:' . esc_attr( vetra_option( 'light_bg' ) ) . ';--vetra-surface:' . esc_attr( vetra_option( 'light_surface' ) ) . ';--vetra-text:' . esc_attr( vetra_option( 'light_text' ) ) . ';--vetra-muted:' . esc_attr( vetra_option( 'light_muted' ) ) . ';--vetra-accent:' . esc_attr( vetra_option( 'light_accent' ) ) . ';--vetra-accent-soft:' . esc_attr( vetra_option( 'light_accent_soft' ) ) . ';--vetra-line:' . esc_attr( vetra_option( 'line_color' ) ) . '}';
	$dark     = 'html[data-theme="dark"]{--vetra-bg:' . esc_attr( vetra_option( 'dark_bg' ) ) . ';--vetra-surface:' . esc_attr( vetra_option( 'dark_surface' ) ) . ';--vetra-text:' . esc_attr( vetra_option( 'dark_text' ) ) . ';--vetra-muted:' . esc_attr( vetra_option( 'dark_muted' ) ) . ';--vetra-accent:' . esc_attr( vetra_option( 'dark_accent' ) ) . ';--vetra-accent-soft:' . esc_attr( vetra_option( 'dark_accent_soft' ) ) . '}';
	$layout   = '.vetra-main{max-width:var(--vetra-content-width);margin-inline:auto}.vetra-topbar{position:' . ( vetra_option( 'header_sticky' ) ? 'sticky' : 'relative' ) . '}.vetra-site-shell{background:var(--vetra-bg)}';
	return $root . $dark . $layout . vetra_option( 'custom_css', '' );
}

function vetra_portal_customizer_css() {
	return vetra_customizer_css();
}

function vetra_customizer_controls_assets() {
	wp_enqueue_style( 'vetra-customizer-controls', VETRA_PORTAL_URI . '/assets/css/customizer.css', array(), VETRA_PORTAL_VERSION );
}
add_action( 'customize_controls_enqueue_scripts', 'vetra_customizer_controls_assets' );
