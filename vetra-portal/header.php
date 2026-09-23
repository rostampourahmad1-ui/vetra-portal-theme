<?php
/**
 * Header and portal shell.
 *
 * @package VetraPortal
 */
?><!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="vetra-site-shell">
	<header class="vetra-topbar">
		<div class="vetra-topbar__brand">
			<button class="vetra-icon-button vetra-menu-toggle js-vetra-menu-toggle" type="button" aria-controls="vetra-sidebar" aria-expanded="false" aria-label="<?php esc_attr_e( 'باز کردن منو', 'vetra-portal' ); ?>">
				<span class="vetra-hamburger"><i></i><i></i><i></i></span>
			</button>
			<a class="vetra-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
				<span class="vetra-brand__mark"><?php echo vetra_brand_logo_url() ? '<img src="' . esc_url( vetra_brand_logo_url() ) . '" alt="" />' : vetra_inline_icon( 'building' ); ?></span>
				<span><strong><?php echo esc_html( vetra_option( 'brand_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'brand_subtitle' ) ); ?></small></span>
			</a>
		</div>
		<?php if ( vetra_option( 'show_global_search' ) ) : ?>
		<div class="vetra-topbar__center">
			<label class="vetra-global-search" for="vetra-global-search">
				<span class="vetra-search-icon"><?php echo vetra_inline_icon( 'file' ); ?></span>
				<input id="vetra-global-search" type="search" placeholder="جستجو در پروژه‌ها، اسناد و فرم‌ها ..." autocomplete="off">
			</label>
		</div>
		<?php endif; ?>
		<div class="vetra-topbar__actions">
			<?php if ( vetra_option( 'show_notifications' ) ) : ?>
			<button class="vetra-icon-button" type="button" aria-label="اعلان‌ها"><span class="vetra-notification-dot"></span><?php echo vetra_nav_icon( 'calendar' ); ?></button>
			<?php endif; ?>
			<?php if ( vetra_option( 'show_user_chip' ) ) : ?>
			<div class="vetra-user-chip">
				<span class="vetra-avatar"><?php $user_name = vetra_current_user_name(); echo esc_html( function_exists( 'mb_substr' ) ? mb_substr( $user_name, 0, 1 ) : substr( $user_name, 0, 1 ) ); ?></span>
				<span><strong><?php echo is_user_logged_in() ? esc_html( wp_get_current_user()->roles[0] ?? 'کاربر پرتال' ) : 'مهمان'; ?></strong><small><?php echo esc_html( vetra_current_user_name() ); ?></small></span>
			</div>
			<?php endif; ?>
		</div>
	</header>
	<div class="vetra-shell-layout<?php echo vetra_option( 'show_sidebar' ) ? '' : ' vetra-portal-no-sidebar'; ?>">
		<?php if ( vetra_option( 'show_sidebar' ) ) : ?>
		<aside id="vetra-sidebar" class="vetra-sidebar" aria-label="ناوبری پرتال">
			<div class="vetra-sidebar__head">
				<span><?php echo esc_html( vetra_option( 'sidebar_title' ) ); ?></span>
				<button class="vetra-icon-button vetra-sidebar-close js-vetra-menu-close" type="button" aria-label="بستن منو">×</button>
			</div>
			<nav class="vetra-nav">
				<?php foreach ( vetra_get_dashboard_nav() as $item ) : ?>
					<a class="vetra-nav__item<?php echo ! empty( $item['active'] ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>">
						<span class="vetra-nav__icon"><?php echo $item['icon'] ? vetra_nav_icon( $item['icon'] ) : ''; ?></span>
						<span><?php echo esc_html( $item['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>
			<div class="vetra-sidebar__footer">
				<span class="vetra-live-indicator"></span>
				<div><strong><?php echo esc_html( vetra_option( 'sidebar_status_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'sidebar_status_subtitle' ) ); ?></small></div>
			</div>
		</aside>
		<?php endif; ?>
		<main id="primary" class="vetra-main">
