<?php
/**
 * Vetra Portal theme bootstrap.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VETRA_PORTAL_VERSION', '3.9.0' );
define( 'VETRA_PORTAL_DIR', get_template_directory() );
define( 'VETRA_PORTAL_URI', get_template_directory_uri() );

require_once VETRA_PORTAL_DIR . '/inc/helpers.php';
require_once VETRA_PORTAL_DIR . '/inc/vetra-icon-library.php';
require_once VETRA_PORTAL_DIR . '/inc/vetra-icons.php';
require_once VETRA_PORTAL_DIR . '/inc/core/settings-schema.php';
require_once VETRA_PORTAL_DIR . '/inc/core/class-loader.php';
if ( ! class_exists( '\\Vetra\\Theme\\Theme' ) ) {
	require_once VETRA_PORTAL_DIR . '/inc/core/class-theme.php';
}
if ( ! class_exists( '\\Vetra\\Theme\\Admin\\Dashboard' ) ) {
	require_once VETRA_PORTAL_DIR . '/inc/admin/class-dashboard.php';
}
require_once VETRA_PORTAL_DIR . '/inc/customizer.php';
require_once VETRA_PORTAL_DIR . '/inc/plugin-manager.php';
require_once VETRA_PORTAL_DIR . '/inc/site-features.php';
require_once VETRA_PORTAL_DIR . '/inc/layouts.php';
require_once VETRA_PORTAL_DIR . '/inc/integrations.php';
require_once VETRA_PORTAL_DIR . '/inc/core/class-updater.php';
require_once VETRA_PORTAL_DIR . '/inc/vetra-design-system.php';
require_once VETRA_PORTAL_DIR . '/template-parts/components.php';

\Vetra\Theme\Theme::boot();
new \Vetra\Theme\Admin\Dashboard();

/** Optional private portal gate for staging and member-only sites. */
function vetra_register_private_portal_setting() {
	register_setting( 'vetra_portal_options', 'vetra_private_portal', array( 'type' => 'boolean', 'default' => false, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
}
add_action( 'admin_init', 'vetra_register_private_portal_setting' );
function vetra_private_portal_gate() {
	if ( ! get_option( 'vetra_private_portal', false ) || is_user_logged_in() || is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) { return; }
	global $pagenow;
	if ( 'wp-login.php' === $pagenow ) { return; }
	$redirect_to = rawurlencode( (string) ( get_permalink() ?: home_url( '/' ) ) );
	wp_safe_redirect( add_query_arg( 'redirect_to', $redirect_to, wp_login_url() ), 302 );
	exit;
}
add_action( 'template_redirect', 'vetra_private_portal_gate', 1 );

/** Keep non-administrative accounts out of wp-admin; front-end forms remain available. */
function vetra_restrict_dashboard() {
	global $pagenow;
	if ( 'customize.php' !== $pagenow && ( is_admin() && ! wp_doing_ajax() && ! ( defined( 'DOING_CRON' ) && DOING_CRON ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'edit_theme_options' ) && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}
add_action( 'admin_init', 'vetra_restrict_dashboard', 1 );

function vetra_portal_setup() {
	load_theme_textdomain( 'vetra-portal-theme', VETRA_PORTAL_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-background', array( 'default-color' => 'f6f7f4' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'elementor' );

	register_nav_menus(
		array(
			'primary' => __( 'منوی اصلی سایت', 'vetra-portal-theme' ),
			'footer'  => __( 'منوی پابرگ سایت', 'vetra-portal-theme' ),
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'نوار کناری اصلی', 'vetra-portal-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'ابزارک‌های نوشته‌ها و آرشیوها.', 'vetra-portal-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array( 'name' => sprintf( __( 'پابرگ ستون %d', 'vetra-portal-theme' ), $i ), 'id' => 'footer-' . $i, 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
	}
	}
add_action( 'after_setup_theme', 'vetra_portal_setup' );

function vetra_primary_menu_fallback() {
	echo '<ul class="vetra-site-nav"><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'خانه', 'vetra-portal-theme' ) . '</a></li><li><a href="' . esc_url( home_url( '/#about' ) ) . '">' . esc_html__( 'درباره وترا', 'vetra-portal-theme' ) . '</a></li><li><a href="' . esc_url( home_url( '/#services' ) ) . '">' . esc_html__( 'خدمات', 'vetra-portal-theme' ) . '</a></li><li><a href="' . esc_url( home_url( '/#contact' ) ) . '">' . esc_html__( 'تماس با ما', 'vetra-portal-theme' ) . '</a></li></ul>';
}

/** Elementor-friendly canvas layout while retaining the standard page content flow. */
function vetra_register_page_templates( $templates ) {
	$templates['templates/full-width.php'] = 'وترا: تمام‌عرض';
	$templates['templates/distraction-free.php'] = 'وترا: بدون حواس‌پرتی';
	$templates['templates/landing.php'] = 'وترا: صفحه فرود';
	return $templates;
}
add_filter( 'theme_page_templates', 'vetra_register_page_templates' );

function vetra_load_page_template( $template ) {
	if ( is_page() && in_array( get_page_template_slug(), array( 'templates/full-width.php', 'templates/distraction-free.php', 'templates/landing.php' ), true ) ) {
		$custom = VETRA_PORTAL_DIR . '/' . get_page_template_slug();
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
	$corporate_css = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'corporate.css' : 'corporate.min.css';
	wp_enqueue_style( 'vetra-corporate', VETRA_PORTAL_URI . '/assets/css/' . $corporate_css, array( 'vetra-portal-style' ), VETRA_PORTAL_VERSION );
	if ( is_rtl() ) {
		wp_enqueue_style( 'vetra-rtl', VETRA_PORTAL_URI . '/rtl.css', array( 'vetra-corporate' ), VETRA_PORTAL_VERSION );
	}
	if ( vetra_option( 'show_search', true ) ) {
		wp_enqueue_style( 'vetra-search', VETRA_PORTAL_URI . '/assets/css/search.css', array( 'vetra-corporate' ), VETRA_PORTAL_VERSION );
	}
	wp_add_inline_style( 'vetra-corporate', vetra_portal_customizer_css() );
	$corporate_js = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'corporate.js' : 'corporate.min.js';
	wp_enqueue_script( 'vetra-portal-script', VETRA_PORTAL_URI . '/assets/js/' . $corporate_js, array(), VETRA_PORTAL_VERSION, true );
	wp_script_add_data( 'vetra-portal-script', 'strategy', 'defer' );
	wp_localize_script(
		'vetra-portal-script',
		'vetraPortal',
		array(
			'themeMode' => vetra_option( 'color_mode' ),
			'themeLabel' => __( 'تغییر حالت رنگی', 'vetra-portal-theme' ),
			'menuOpenLabel' => __( 'باز کردن منوی سایت', 'vetra-portal-theme' ),
			'menuCloseLabel' => __( 'بستن منوی سایت', 'vetra-portal-theme' ),
			'searchOpenLabel' => __( 'باز کردن جست‌وجو', 'vetra-portal-theme' ),
			'searchCloseLabel' => __( 'بستن جست‌وجو', 'vetra-portal-theme' ),
			'backToTopLabel' => __( 'بازگشت به ابتدای صفحه', 'vetra-portal-theme' ),
			'showBackToTop' => (bool) vetra_option( 'show_back_to_top', true ),
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
	if ( vetra_option( 'header_sticky', true ) ) { $classes[] = 'vetra-header-is-sticky'; }
	if ( vetra_option( 'show_back_to_top', true ) ) { $classes[] = 'vetra-has-back-to-top'; }
	if ( is_singular() && comments_open() ) { $classes[] = 'vetra-has-comments'; }

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
				<span class="vetra-user-bar__guest"><?php esc_html_e( 'مهمان عزیز', 'vetra-portal-theme' ); ?></span>
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
		<?php
		$vetra_bar_icons = array(
			'home'     => 'building',
			'services' => 'settings',
						'contact'  => 'message',
			'search'   => 'search',
			'login'    => 'login',
			'logout'   => 'logout',
			'filter'   => 'filter',
		);
		for ( $i = 1; $i <= 4; $i++ ) :
			$vetra_bar_key  = (string) vetra_option( 'bottom_bar_item_' . $i . '_icon', '' );
			$vetra_bar_icon = isset( $vetra_bar_icons[ $vetra_bar_key ] ) ? $vetra_bar_icons[ $vetra_bar_key ] : '';
			?>
			<a href="<?php echo esc_url( vetra_option( 'bottom_bar_item_' . $i . '_url', '/' ) ); ?>">
				<?php if ( $vetra_bar_icon && function_exists( 'vetra_icon' ) ) : ?>
					<span class="vetra-bottom-bar__icon vetra-bottom-bar__icon--svg"><?php echo vetra_icon( $vetra_bar_icon, array( 'size' => 22, 'decorative' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- icon is escaped. ?></span>
				<?php else : ?>
					<span class="vetra-bottom-bar__icon <?php echo esc_attr( $vetra_bar_key ); ?>"></span>
				<?php endif; ?>
				<span><?php echo esc_html( vetra_option( 'bottom_bar_item_' . $i . '_text', '' ) ); ?></span>
			</a>
		<?php endfor; ?>
	</nav>
	<?php
}
add_action( 'wp_footer', 'vetra_render_bottom_bar', 5 );
