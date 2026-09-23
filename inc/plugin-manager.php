<?php
/**
 * Free dependency manager for the corporate theme.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_plugin_manifest() {
	return array(
		'elementor' => array(
			'name' => 'Elementor',
			'description' => 'صفحه‌ساز رایگان برای صفحات خدمات و پروژه‌ها.',
			'url' => 'https://downloads.wordpress.org/plugin/elementor.latest-stable.zip',
			'file' => 'elementor/elementor.php',
		),
		'elementskit-lite' => array(
			'name' => 'ElementsKit Lite',
			'description' => 'ویجت‌های تکمیلی رایگان Elementor.',
			'url' => 'https://downloads.wordpress.org/plugin/elementskit-lite.latest-stable.zip',
			'file' => 'elementskit-lite/elementskit-lite.php',
		),
		'contact-form-7' => array(
			'name' => 'Contact Form 7',
			'description' => 'فرم تماس رایگان برای بخش ارتباط با ما.',
			'url' => 'https://downloads.wordpress.org/plugin/contact-form-7.latest-stable.zip',
			'file' => 'contact-form-7/wp-contact-form-7.php',
		),
	);
}

function vetra_plugin_is_active( $file ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	return is_plugin_active( $file );
}

function vetra_install_plugin_package( $package ) {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return new WP_Error( 'vetra_no_permission', 'مجوز نصب افزونه را ندارید.' );
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$before = get_plugins();
	$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
	$result = $upgrader->install( $package );
	if ( is_wp_error( $result ) ) {
		return $result;
	}
	if ( ! $result ) {
		return new WP_Error( 'vetra_install_failed', 'نصب افزونه انجام نشد.' );
	}
	foreach ( array_diff_key( get_plugins(), $before ) as $file => $data ) {
		activate_plugin( $file );
		break;
	}
	return true;
}

function vetra_plugin_manager_admin_menu() {
	add_theme_page( 'افزونه‌های رایگان وترا', 'افزونه‌های رایگان', 'manage_options', 'vetra-portal-plugins', 'vetra_plugin_manager_page' );
}
add_action( 'admin_menu', 'vetra_plugin_manager_admin_menu' );

function vetra_plugin_manager_handle_actions() {
	if ( ! is_admin() || ! current_user_can( 'install_plugins' ) || empty( $_POST['vetra_plugin_action'] ) ) {
		return;
	}
	check_admin_referer( 'vetra_plugin_manager' );
	$manifest = vetra_plugin_manifest();
	$action = sanitize_key( wp_unslash( $_POST['vetra_plugin_action'] ) );
	$errors = array();
	if ( 'install' === $action ) {
		$slug = sanitize_key( wp_unslash( $_POST['plugin_slug'] ?? '' ) );
		if ( isset( $manifest[ $slug ] ) && ! vetra_plugin_is_active( $manifest[ $slug ]['file'] ) ) {
			$result = vetra_install_plugin_package( $manifest[ $slug ]['url'] );
			if ( is_wp_error( $result ) ) $errors[] = $result->get_error_message();
		}
	}
	if ( 'install-all' === $action ) {
		foreach ( $manifest as $plugin ) {
			if ( ! vetra_plugin_is_active( $plugin['file'] ) ) {
				$result = vetra_install_plugin_package( $plugin['url'] );
				if ( is_wp_error( $result ) ) $errors[] = $result->get_error_message();
			}
		}
	}
	$url = wp_get_referer() ?: admin_url( 'themes.php?page=vetra-portal-plugins' );
	$url = add_query_arg( $errors ? 'vetra_plugin_error' : 'vetra_plugin_message', rawurlencode( $errors ? implode( ' ', $errors ) : 'success' ), $url );
	wp_safe_redirect( $url );
	exit;
}
add_action( 'admin_init', 'vetra_plugin_manager_handle_actions' );

function vetra_plugin_manager_page() {
	$manifest = vetra_plugin_manifest();
	?>
	<div class="wrap" dir="rtl">
		<h1>افزونه‌های رایگان وترا</h1>
		<p>قالب شرکتی وترا بدون افزونه هم قابل استفاده است. این افزونه‌های رایگان فقط برای ساخت صفحات پیشرفته و فرم تماس پیشنهاد می‌شوند.</p>
		<?php if ( isset( $_GET['vetra_plugin_message'] ) ) : ?><div class="notice notice-success"><p>افزونه‌ها با موفقیت نصب شدند.</p></div><?php endif; ?>
		<?php if ( isset( $_GET['vetra_plugin_error'] ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( wp_unslash( $_GET['vetra_plugin_error'] ) ); ?></p></div><?php endif; ?>
		<table class="widefat striped" style="max-width:1000px;margin-top:20px"><thead><tr><th>افزونه</th><th>کاربرد</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
		<?php foreach ( $manifest as $slug => $plugin ) : $active = vetra_plugin_is_active( $plugin['file'] ); ?>
			<tr><td><strong><?php echo esc_html( $plugin['name'] ); ?></strong></td><td><?php echo esc_html( $plugin['description'] ); ?></td><td><?php echo $active ? '<span style="color:#008a20">فعال</span>' : '<span style="color:#b32d2e">نصب نیست</span>'; ?></td><td><?php if ( ! $active ) : ?><form method="post"><input type="hidden" name="vetra_plugin_action" value="install"><input type="hidden" name="plugin_slug" value="<?php echo esc_attr( $slug ); ?>"><?php wp_nonce_field( 'vetra_plugin_manager' ); ?><button class="button button-primary">نصب و فعال‌سازی</button></form><?php else : ?>آماده استفاده<?php endif; ?></td></tr>
		<?php endforeach; ?>
		</tbody></table>
		<form method="post" style="margin-top:18px"><input type="hidden" name="vetra_plugin_action" value="install-all"><?php wp_nonce_field( 'vetra_plugin_manager' ); ?><button class="button button-primary">نصب همه افزونه‌های رایگان</button></form>
	</div>
	<?php
}
