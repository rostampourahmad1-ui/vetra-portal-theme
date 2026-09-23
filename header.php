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
			<a class="vetra-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
				<span class="vetra-brand__mark"><?php echo vetra_brand_logo_url() ? '<img src="' . esc_url( vetra_brand_logo_url() ) . '" alt="" />' : vetra_inline_icon( 'building' ); ?></span>
				<span><strong><?php echo esc_html( vetra_option( 'brand_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'brand_subtitle' ) ); ?></small></span>
			</a>
		</div>
		<div class="vetra-topbar__center">
			<nav class="vetra-topnav" aria-label="ناوبری اصلی">
				<?php foreach ( vetra_get_portal_nav() as $item ) : if ( ! in_array( $item['label'], array( 'پروژه‌ها', 'مرکز فرم‌ها', 'گزارش‌ها' ), true ) ) { continue; } ?>
					<a class="vetra-topnav__item<?php echo ! empty( $item['active'] ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( 'مرکز فرم‌ها' === $item['label'] ? 'فرم‌ها' : $item['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
		</div>
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
	<div class="vetra-shell-layout">
		<main id="primary" class="vetra-main">
