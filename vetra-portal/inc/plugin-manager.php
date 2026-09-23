<?php
/**
 * Dependency manager for the portal theme.
 *
 * Free plugins are fetched from WordPress.org. Premium plugins are never
 * downloaded from unofficial sources and can be installed from a licensed ZIP.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_plugin_manifest() {
	return array(
		'elementor' => array(
			'name'       => 'Elementor',
			'description'=> 'صفحه‌ساز پایه برای ساخت صفحات پرتال.',
			'free'       => true,
			'url'        => 'https://downloads.wordpress.org/plugin/elementor.latest-stable.zip',
			'file'       => 'elementor/elementor.php',
		),
		'elementskit-lite' => array(
			'name'       => 'ElementsKit Lite',
			'description'=> 'ویجت‌های تکمیلی Elementor برای هدر، تب و ابزارهای رابط.',
			'free'       => true,
			'url'        => 'https://downloads.wordpress.org/plugin/elementskit-lite.latest-stable.zip',
			'file'       => 'elementskit-lite/elementskit-lite.php',
		),
		'advanced-custom-fields-pro' => array(
			'name'       => 'Advanced Custom Fields Pro',
			'description'=> 'فیلدهای داینامیک، گروه فیلد و import برای فرم‌های عملیاتی.',
			'free'       => false,
			'file'       => 'advanced-custom-fields-pro/acf.php',
		),
		'elementor-pro' => array(
			'name'       => 'Elementor Pro',
			'description'=> 'Loop Grid، Dynamic Tags و Theme Builder حرفه‌ای.',
			'free'       => false,
			'file'       => 'elementor-pro/elementor-pro.php',
		),
		'elementskit-pro' => array(
			'name'       => 'ElementsKit Pro',
			'description'=> 'فیلترها، تب‌های پیشرفته و اجزای حرفه‌ای Elementor.',
			'free'       => false,
			'file'       => 'elementskit-pro/elementskit-pro.php',
		),
	);
}

function vetra_plugin_is_active( $file ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return is_plugin_active( $file );
}

function vetra_missing_plugins() {
	$missing = array();
	foreach ( vetra_plugin_manifest() as $slug => $plugin ) {
		if ( ! vetra_plugin_is_active( $plugin['file'] ) ) {
			$missing[ $slug ] = $plugin;
		}
	}
	return $missing;
}

function vetra_plugin_manager_admin_menu() {
	add_theme_page( 'افزونه‌های پرتال وترا', 'افزونه‌های پرتال', 'manage_options', 'vetra-portal-plugins', 'vetra_plugin_manager_page' );
}
add_action( 'admin_menu', 'vetra_plugin_manager_admin_menu' );

function vetra_plugin_manager_notice() {
	$screen = get_current_screen();
	if ( ! current_user_can( 'manage_options' ) || ( $screen && 'appearance_page_vetra-portal-plugins' === $screen->id ) ) {
		return;
	}

	$missing = vetra_missing_plugins();
	if ( ! $missing ) {
		return;
	}

	printf(
		'<div class="notice notice-warning is-dismissible"><p><strong>پرتال وترا:</strong> %d افزونه برای فعال‌کردن قابلیت‌های کامل موجود نیست. <a href="%s">مدیریت افزونه‌های پرتال</a></p></div>',
		count( $missing ),
		esc_url( admin_url( 'themes.php?page=vetra-portal-plugins' ) )
	);
}
add_action( 'admin_notices', 'vetra_plugin_manager_notice' );

function vetra_plugin_manager_handle_actions() {
	if ( ! is_admin() || ! current_user_can( 'install_plugins' ) || empty( $_POST['vetra_plugin_action'] ) ) {
		return;
	}

	check_admin_referer( 'vetra_plugin_manager' );
	$manifest = vetra_plugin_manifest();
	$action   = sanitize_key( wp_unslash( $_POST['vetra_plugin_action'] ) );
	$result   = false;

	if ( 'install-free' === $action ) {
		$slug = sanitize_key( wp_unslash( $_POST['plugin_slug'] ?? '' ) );
		if ( isset( $manifest[ $slug ] ) && $manifest[ $slug ]['free'] ) {
			$result = vetra_install_plugin_package( $manifest[ $slug ]['url'] );
		}
	}

	if ( 'install-free-bundle' === $action ) {
		$errors = array();
		foreach ( $manifest as $plugin ) {
			if ( ! empty( $plugin['free'] ) && ! vetra_plugin_is_active( $plugin['file'] ) ) {
				$installed = vetra_install_plugin_package( $plugin['url'] );
				if ( is_wp_error( $installed ) ) {
					$errors[] = $installed->get_error_message();
				}
			}
		}
		$result = $errors ? new WP_Error( 'vetra_bundle_install_failed', implode( ' ', $errors ) ) : true;
	}

	if ( 'install-upload' === $action && ! empty( $_FILES['plugin_zip']['tmp_name'] ) ) {
		$result = vetra_install_plugin_package( $_FILES['plugin_zip']['tmp_name'] );
	}

	if ( $result && ! is_wp_error( $result ) ) {
		wp_safe_redirect( add_query_arg( 'vetra_plugin_message', 'success', wp_get_referer() ?: admin_url( 'themes.php?page=vetra-portal-plugins' ) ) );
		exit;
	}

	if ( is_wp_error( $result ) ) {
		wp_safe_redirect( add_query_arg( 'vetra_plugin_error', rawurlencode( $result->get_error_message() ), wp_get_referer() ?: admin_url( 'themes.php?page=vetra-portal-plugins' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'vetra_plugin_manager_handle_actions' );

function vetra_install_plugin_package( $package ) {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return new WP_Error( 'vetra_no_permission', 'مجوز نصب افزونه را ندارید.' );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$before  = get_plugins();
	$skin    = new Automatic_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );
	$result  = $upgrader->install( $package );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	if ( ! $result ) {
		return new WP_Error( 'vetra_install_failed', 'نصب افزونه انجام نشد. دسترسی فایل‌ها و ZIP را بررسی کنید.' );
	}

	$plugins = get_plugins();
	$new_plugins = array_diff_key( $plugins, $before );
	foreach ( $new_plugins as $file => $data ) {
		activate_plugin( $file );
		break;
	}

	return true;
}

function vetra_plugin_manager_page() {
	$manifest = vetra_plugin_manifest();
	$missing  = vetra_missing_plugins();
	?>
	<div class="wrap" dir="rtl">
		<h1>افزونه‌های پرتال وترا</h1>
		<p>قالب بدون افزونه هم رندر می‌شود، اما برای قابلیت‌های کامل داینامیک، افزونه‌های زیر را نصب کنید.</p>
		<?php if ( isset( $_GET['vetra_plugin_message'] ) ) : ?><div class="notice notice-success"><p>عملیات افزونه با موفقیت انجام شد.</p></div><?php endif; ?>
		<?php if ( isset( $_GET['vetra_plugin_error'] ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( wp_unslash( $_GET['vetra_plugin_error'] ) ); ?></p></div><?php endif; ?>
		<table class="widefat striped" style="max-width:1000px;margin-top:20px">
			<thead><tr><th>افزونه</th><th>کاربرد</th><th>وضعیت</th><th>عملیات</th></tr></thead>
			<tbody>
			<?php foreach ( $manifest as $slug => $plugin ) : $active = vetra_plugin_is_active( $plugin['file'] ); ?>
				<tr><td><strong><?php echo esc_html( $plugin['name'] ); ?></strong></td><td><?php echo esc_html( $plugin['description'] ); ?></td><td><?php echo $active ? '<span style="color:#008a20">فعال</span>' : '<span style="color:#b32d2e">نصب یا فعال نیست</span>'; ?></td><td><?php if ( ! $active && ! empty( $plugin['free'] ) ) : ?><form method="post"><input type="hidden" name="vetra_plugin_action" value="install-free"><input type="hidden" name="plugin_slug" value="<?php echo esc_attr( $slug ); ?>"><?php wp_nonce_field( 'vetra_plugin_manager' ); ?><button class="button button-primary">نصب و فعال‌سازی</button></form><?php elseif ( ! $active ) : ?>از بخش ZIP لایسنس‌دار نصب کنید<?php else : ?>آماده استفاده<?php endif; ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<form method="post" style="margin-top:18px"><input type="hidden" name="vetra_plugin_action" value="install-free-bundle"><?php wp_nonce_field( 'vetra_plugin_manager' ); ?><button class="button button-primary">نصب همه افزونه‌های آزاد</button></form>
		<h2 style="margin-top:35px">نصب افزونه لایسنس‌دار</h2>
		<p>ZIP رسمی Elementor Pro، ACF Pro یا ElementsKit Pro را از حساب خریداری‌شده دریافت و در این بخش نصب کنید.</p>
		<form method="post" enctype="multipart/form-data"><input type="hidden" name="vetra_plugin_action" value="install-upload"><?php wp_nonce_field( 'vetra_plugin_manager' ); ?><input type="file" name="plugin_zip" accept=".zip" required> <button class="button button-primary">نصب ZIP افزونه</button></form>
	</div>
	<?php
}
