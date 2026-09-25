<?php
/**
 * Vetra Portal theme bootstrap.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VETRA_PORTAL_VERSION', '3.4.0' );
define( 'VETRA_PORTAL_DIR', get_template_directory() );
define( 'VETRA_PORTAL_URI', get_template_directory_uri() );

require_once VETRA_PORTAL_DIR . '/inc/helpers.php';
require_once VETRA_PORTAL_DIR . '/inc/customizer.php';
require_once VETRA_PORTAL_DIR . '/inc/plugin-manager.php';
require_once VETRA_PORTAL_DIR . '/inc/projects.php';
require_once VETRA_PORTAL_DIR . '/inc/site-features.php';
require_once VETRA_PORTAL_DIR . '/inc/layouts.php';

/** Keep non-administrative accounts out of wp-admin; front-end forms remain available. */
function vetra_restrict_dashboard() {
	if ( is_admin() && ! wp_doing_ajax() && ! ( defined( 'DOING_CRON' ) && DOING_CRON ) && ! current_user_can( 'manage_options' ) && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}
add_action( 'admin_init', 'vetra_restrict_dashboard', 1 );

function vetra_portal_setup() {
	load_theme_textdomain( 'vetra-portal', VETRA_PORTAL_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-background', array( 'default-color' => 'f6f7f4' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'elementor' );

	register_nav_menus(
		array(
			'primary' => __( 'منوی اصلی سایت', 'vetra-portal' ),
		)
	);
}
add_action( 'after_setup_theme', 'vetra_portal_setup' );

function vetra_primary_menu_fallback() {
	echo '<ul class="vetra-site-nav"><li><a href="' . esc_url( home_url( '/' ) ) . '">خانه</a></li><li><a href="' . esc_url( home_url( '/#about' ) ) . '">درباره وترا</a></li><li><a href="' . esc_url( home_url( '/#services' ) ) . '">خدمات</a></li><li><a href="' . esc_url( home_url( '/#projects' ) ) . '">پروژه‌ها</a></li><li><a href="' . esc_url( home_url( '/#contact' ) ) . '">تماس با ما</a></li></ul>';
}

/** Elementor-friendly canvas layout while retaining the standard page content flow. */
function vetra_register_page_templates( $templates ) {
	$templates['templates/full-width.php'] = 'وترا: تمام‌عرض';
	return $templates;
}
add_filter( 'theme_page_templates', 'vetra_register_page_templates' );

function vetra_load_page_template( $template ) {
	if ( is_page() && 'templates/full-width.php' === get_page_template_slug() ) {
		$custom = VETRA_PORTAL_DIR . '/templates/full-width.php';
		if ( is_readable( $custom ) ) { return $custom; }
	}
	return $template;
}
add_filter( 'template_include', 'vetra_load_page_template' );

function vetra_portal_enqueue_assets() {
	// Font CDNs.
	$font_url = '';
	if ( vetra_option( 'load_vazirmatn' ) ) {
		$font_url = 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap';
	} elseif ( vetra_option( 'load_estedad' ) ) {
		$font_url = 'https://cdn.jsdelivr.net/gh/aminabedi68/Estedad@master/Font/Styles.css';
	} elseif ( vetra_option( 'load_dana' ) ) {
		$font_url = 'https://cdn.jsdelivr.net/gh/fontsource/dana@latest/index.css';
	}
	if ( $font_url ) {
		wp_enqueue_style( 'vetra-font-cdn', $font_url, array(), VETRA_PORTAL_VERSION );
	}

	wp_enqueue_style( 'vetra-portal-style', get_stylesheet_uri(), array(), VETRA_PORTAL_VERSION );
	wp_enqueue_style( 'vetra-corporate', VETRA_PORTAL_URI . '/assets/css/corporate.css', array( 'vetra-portal-style' ), VETRA_PORTAL_VERSION );
	wp_add_inline_style( 'vetra-corporate', vetra_portal_customizer_css() );
	wp_enqueue_script( 'vetra-portal-script', VETRA_PORTAL_URI . '/assets/js/corporate.js', array(), VETRA_PORTAL_VERSION, true );
	wp_localize_script(
		'vetra-portal-script',
		'vetraPortal',
		array(
			'themeMode' => vetra_option( 'color_mode' ),
			'themeLabel' => __( 'تغییر حالت رنگی', 'vetra-portal' ),
			'pwaEnabled' => (bool) vetra_option( 'pwa_enabled' ),
			'swUrl' => home_url( '/vetra-sw' ),
			'installPrompt' => (bool) vetra_option( 'pwa_install_prompt', true ),
			'installText' => sanitize_text_field( vetra_option( 'pwa_install_text', 'برای دسترسی سریع‌تر، وب‌اپ وترا را نصب کنید.' ) ),
			'installDelay' => absint( vetra_option( 'pwa_install_delay', 5 ) ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vetra_portal_enqueue_assets' );

function vetra_portal_body_classes( $classes ) {
	$classes[] = 'vetra-portal-theme';
	if ( is_page() && 'templates/full-width.php' === get_page_template_slug() ) { $classes[] = 'vetra-page-full-width'; }
	$classes[] = is_user_logged_in() ? 'vetra-user-logged-in' : 'vetra-user-logged-out';
	if ( vetra_option( 'mobile_menu_enabled', true ) ) {
		$classes[] = 'drawer' === vetra_option( 'mobile_menu_style', 'drawer' ) ? 'vetra-mobile-drawer' : 'vetra-mobile-bottom-sheet';
	}
	if ( vetra_option( 'mobile_menu_enabled', true ) && vetra_option( 'sticky_bottom_bar' ) ) {
		$classes[] = 'vetra-has-bottom-bar';
	}
	if ( vetra_option( 'background_enabled', false ) ) {
		$classes[] = 'vetra-bg-active';
	}

	return $classes;
}
add_filter( 'body_class', 'vetra_portal_body_classes' );

function vetra_portal_excerpt_length() {
	return 18;
}
add_filter( 'excerpt_length', 'vetra_portal_excerpt_length' );

/**
 * Render user bar on selected pages.
 */
function vetra_render_user_bar() {
	if ( ! vetra_option( 'show_user_bar' ) ) {
		return;
	}
	if ( ! is_page() ) {
		return;
	}
	$pages = vetra_option( 'user_bar_pages', array() );
	if ( $pages && ! in_array( get_the_ID(), $pages, true ) ) {
		return;
	}

	$show_avatar = vetra_option( 'show_user_avatar', true );
	$show_name   = vetra_option( 'show_user_name', true );
	$show_list   = vetra_option( 'show_user_list', false );
	?>
	<aside class="vetra-user-bar">
		<div class="vetra-container vetra-user-bar__inner">
			<?php if ( is_user_logged_in() ) : ?>
				<div class="vetra-user-bar__current">
					<?php if ( $show_avatar ) : ?><?php echo get_avatar( get_current_user_id(), 40, '', '', array( 'class' => 'vetra-user-bar__avatar' ) ); ?><?php endif; ?>
					<?php if ( $show_name ) : ?><span class="vetra-user-bar__name"><?php echo esc_html( wp_get_current_user()->display_name ); ?></span><?php endif; ?>
				</div>
			<?php else : ?>
				<span class="vetra-user-bar__guest"><?php esc_html_e( 'مهمان عزیز', 'vetra-portal' ); ?></span>
			<?php endif; ?>
			<?php if ( $show_list ) : ?>
				<div class="vetra-user-bar__list">
					<?php
					$users = get_users( array( 'number' => 8, 'orderby' => 'display_name' ) );
					foreach ( $users as $u ) {
						echo '<span>' . get_avatar( $u->ID, 32, '', '', array( 'class' => 'vetra-user-bar__avatar vetra-user-bar__avatar--small' ) ) . '</span>';
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}
add_action( 'vetra_after_header', 'vetra_render_user_bar' );

/**
 * Render sticky bottom mobile menu.
 */
function vetra_render_bottom_bar() {
	if ( ! vetra_option( 'mobile_menu_enabled', true ) || ! vetra_option( 'sticky_bottom_bar' ) ) {
		return;
	}
	?>
	<nav class="vetra-bottom-bar" aria-label="منوی سریع موبایل">
		<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
			<a href="<?php echo esc_url( vetra_option( 'bottom_bar_item_' . $i . '_url', '/' ) ); ?>">
				<span class="vetra-bottom-bar__icon <?php echo esc_attr( vetra_option( 'bottom_bar_item_' . $i . '_icon', '' ) ); ?>"></span>
				<span><?php echo esc_html( vetra_option( 'bottom_bar_item_' . $i . '_text', '' ) ); ?></span>
			</a>
		<?php endfor; ?>
	</nav>
	<?php
}
add_action( 'wp_footer', 'vetra_render_bottom_bar', 5 );
