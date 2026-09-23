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
			'type' => 'url',
		),
		'elementskit-lite' => array(
			'name' => 'ElementsKit Lite',
			'description' => 'ویجت‌های تکمیلی رایگان Elementor.',
			'url' => 'https://downloads.wordpress.org/plugin/elementskit-lite.latest-stable.zip',
			'file' => 'elementskit-lite/elementskit-lite.php',
			'type' => 'url',
		),
		'gravityforms' => array(
			'name' => 'Gravity Forms',
			'description' => 'فرم‌ساز حرفه‌ای. نیازمند فایل ZIP رسمی خریداری‌شده است.',
			'file' => 'gravityforms/gravityforms.php',
			'type' => 'zip',
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
	add_theme_page( 'افزونه‌های وترا', 'افزونه‌های وترا', 'manage_options', 'vetra-portal-plugins', 'vetra_plugin_manager_page' );
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
	$messages = array();

	if ( 'install' === $action ) {
		$slug = sanitize_key( wp_unslash( $_POST['plugin_slug'] ?? '' ) );
		if ( isset( $manifest[ $slug ] ) && ! vetra_plugin_is_active( $manifest[ $slug ]['file'] ) ) {
			if ( 'zip' === $manifest[ $slug ]['type'] ) {
				if ( ! empty( $_FILES['plugin_zip']['tmp_name'] ) ) {
					$uploaded = wp_handle_upload( $_FILES['plugin_zip'], array( 'test_form' => false, 'mimes' => array( 'zip' => 'application/zip' ) ) );
					if ( isset( $uploaded['file'] ) ) {
						$result = vetra_install_plugin_package( $uploaded['file'] );
						if ( is_wp_error( $result ) ) {
							$errors[] = $result->get_error_message();
						} else {
							$messages[] = $manifest[ $slug ]['name'] . ' نصب و فعال شد.';
						}
					} else {
						$errors[] = 'آپلود فایل ZIP ناموفق بود.';
					}
				} else {
					$errors[] = 'لطفاً فایل ZIP رسمی افزونه را انتخاب کنید.';
				}
			} elseif ( ! empty( $manifest[ $slug ]['url'] ) ) {
				$result = vetra_install_plugin_package( $manifest[ $slug ]['url'] );
				if ( is_wp_error( $result ) ) {
					$errors[] = $result->get_error_message();
				} else {
					$messages[] = $manifest[ $slug ]['name'] . ' نصب و فعال شد.';
				}
			}
		}
	}

	if ( 'install-all' === $action ) {
		foreach ( $manifest as $slug => $plugin ) {
			if ( vetra_plugin_is_active( $plugin['file'] ) || 'zip' === $plugin['type'] ) {
				continue;
			}
			$result = vetra_install_plugin_package( $plugin['url'] );
			if ( is_wp_error( $result ) ) {
				$errors[] = $result->get_error_message();
			} else {
				$messages[] = $plugin['name'] . ' نصب و فعال شد.';
			}
		}
	}

	$url = wp_get_referer() ?: admin_url( 'themes.php?page=vetra-portal-plugins' );
	if ( $errors ) {
		$url = add_query_arg( 'vetra_plugin_error', rawurlencode( implode( ' ', $errors ) ), $url );
	}
	if ( $messages ) {
		$url = add_query_arg( 'vetra_plugin_message', rawurlencode( implode( ' ', $messages ) ), $url );
	}
	wp_safe_redirect( $url );
	exit;
}
add_action( 'admin_init', 'vetra_plugin_manager_handle_actions' );

function vetra_plugin_manager_page() {
	$manifest = vetra_plugin_manifest();
	?>
	<div class="wrap" dir="rtl">
		<h1>افزونه‌های وترا</h1>
		<p>قالب شرکتی وترا بدون افزونه هم قابل استفاده است. این افزونه‌ها فقط برای صفحات پیشرفته و فرم تماس پیشنهاد می‌شوند.</p>
		<?php if ( isset( $_GET['vetra_plugin_message'] ) ) : ?><div class="notice notice-success"><p><?php echo esc_html( wp_unslash( $_GET['vetra_plugin_message'] ) ); ?></p></div><?php endif; ?>
		<?php if ( isset( $_GET['vetra_plugin_error'] ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( wp_unslash( $_GET['vetra_plugin_error'] ) ); ?></p></div><?php endif; ?>
		<table class="widefat striped" style="max-width:1000px;margin-top:20px"><thead><tr><th>افزونه</th><th>کاربرد</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
		<?php foreach ( $manifest as $slug => $plugin ) : $active = vetra_plugin_is_active( $plugin['file'] ); ?>
			<tr>
				<td><strong><?php echo esc_html( $plugin['name'] ); ?></strong></td>
				<td><?php echo esc_html( $plugin['description'] ); ?></td>
				<td><?php echo $active ? '<span style="color:#008a20">فعال</span>' : '<span style="color:#b32d2e">نصب نیست</span>'; ?></td>
				<td>
				<?php if ( ! $active ) : ?>
					<form method="post" enctype="multipart/form-data">
						<input type="hidden" name="vetra_plugin_action" value="install">
						<input type="hidden" name="plugin_slug" value="<?php echo esc_attr( $slug ); ?>">
						<?php wp_nonce_field( 'vetra_plugin_manager' ); ?>
						<?php if ( 'zip' === $plugin['type'] ) : ?>
							<input type="file" name="plugin_zip" accept=".zip" required style="margin-bottom:8px;display:block">
						<?php endif; ?>
						<button class="button button-primary">نصب و فعال‌سازی</button>
					</form>
				<?php else : ?>آماده استفاده<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody></table>
		<form method="post" style="margin-top:18px"><input type="hidden" name="vetra_plugin_action" value="install-all"><?php wp_nonce_field( 'vetra_plugin_manager' ); ?><button class="button button-primary">نصب همه افزونه‌های رایگان</button></form>
	</div>
	<?php
}
