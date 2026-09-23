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
				<span class="vetra-brand__mark"><?php echo vetra_inline_icon( 'building' ); ?></span>
				<span><strong>پرتال مدیریت ساخت</strong><small>گروه ساختمانی وترا</small></span>
			</a>
		</div>
		<div class="vetra-topbar__center">
			<label class="vetra-global-search" for="vetra-global-search">
				<span class="vetra-search-icon"><?php echo vetra_inline_icon( 'file' ); ?></span>
				<input id="vetra-global-search" type="search" placeholder="جستجو در پروژه‌ها، اسناد و فرم‌ها ..." autocomplete="off">
			</label>
		</div>
		<div class="vetra-topbar__actions">
			<button class="vetra-icon-button" type="button" aria-label="اعلان‌ها"><span class="vetra-notification-dot"></span><?php echo vetra_nav_icon( 'calendar' ); ?></button>
			<div class="vetra-user-chip">
				<span class="vetra-avatar">م</span>
				<span><strong>مدیر پروژه</strong><small>علی محمدی</small></span>
			</div>
		</div>
	</header>
	<div class="vetra-shell-layout">
		<aside id="vetra-sidebar" class="vetra-sidebar" aria-label="ناوبری پرتال">
			<div class="vetra-sidebar__head">
				<span>ناوبری اصلی</span>
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
				<div><strong>سامانه عملیاتی فعال</strong><small>همگام با ساخت آینده</small></div>
			</div>
		</aside>
		<main id="primary" class="vetra-main">
