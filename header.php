<?php
/**
 * Corporate site header.
 *
 * @package VetraPortal
 */
$theme_mode = vetra_option( 'color_mode', 'system' );
?><!doctype html>
<html <?php language_attributes(); ?> dir="rtl" data-theme="<?php echo esc_attr( 'dark' === $theme_mode ? 'dark' : 'light' ); ?>" data-theme-mode="<?php echo esc_attr( $theme_mode ); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! get_site_icon_url() ) : ?><link rel="icon" href="<?php echo esc_url( VETRA_PORTAL_URI . '/assets/images/favicon.png' ); ?>" type="image/png"><?php endif; ?>
	<?php wp_head(); ?>
</head>
	<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="vetra-site-shell">
	<?php $vetra_has_custom_header = function_exists( 'vetra_render_layout' ) && vetra_render_layout( 'header' ); ?>
	<?php if ( ! $vetra_has_custom_header && vetra_option( 'header_enabled', true ) ) : ?>
	<header class="vetra-topbar">
		<div class="vetra-container vetra-topbar__inner">
			<a class="vetra-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
				<span class="vetra-brand__mark"><?php if ( vetra_brand_logo_url() ) : ?><img class="vetra-logo-light" src="<?php echo esc_url( vetra_brand_logo_url( 'light' ) ); ?>" alt=""><img class="vetra-logo-dark" src="<?php echo esc_url( vetra_brand_logo_url( 'dark' ) ); ?>" alt=""><?php else : echo vetra_inline_icon( 'building' ); endif; ?></span>
				<span class="vetra-brand__copy"><strong><?php echo esc_html( vetra_option( 'brand_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'brand_subtitle' ) ); ?></small></span>
			</a>
			<?php if ( vetra_option( 'mobile_menu_enabled', true ) || ! wp_is_mobile() ) : wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'vetra-site-nav', 'fallback_cb' => 'vetra_primary_menu_fallback', 'depth' => 1 ) ); endif; ?>
			<div class="vetra-topbar__actions">
				<?php if ( vetra_option( 'show_theme_switch', true ) ) : ?><button class="vetra-mode-toggle js-vetra-mode-toggle" type="button" aria-label="<?php esc_attr_e( 'تغییر حالت رنگی', 'vetra-portal' ); ?>"><span class="vetra-mode-toggle__sun"><?php echo vetra_inline_icon( 'sun' ); ?></span><span class="vetra-mode-toggle__moon"><?php echo vetra_inline_icon( 'moon' ); ?></span></button><?php endif; ?>
				<?php if ( vetra_option( 'header_cta_enabled', true ) ) : ?><a class="vetra-header-cta" href="<?php echo esc_url( vetra_option( 'header_cta_url' ) ); ?>"><?php echo esc_html( vetra_option( 'header_cta_text' ) ); ?><span><?php echo vetra_inline_icon( 'arrow-up' ); ?></span></a><?php endif; ?>
				<?php if ( vetra_option( 'mobile_menu_enabled', true ) ) : ?><button class="vetra-mobile-toggle js-vetra-mobile-toggle" type="button" aria-label="منوی سایت" aria-expanded="false"><?php echo vetra_inline_icon( 'menu' ); ?></button><?php endif; ?>
			</div>
		</div>
	</header>
	<?php endif; ?>
	<?php do_action( 'vetra_after_header' ); ?>
	<main id="primary" class="vetra-main">
