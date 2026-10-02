<?php
/**
 * Installs and activates the bundled Vetra Plus plugin when the theme is activated.
 *
 * @package VetraPortal
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install the bundled Vetra Plus plugin into the normal WordPress plugins directory.
 *
 * This runs only on theme activation, never overwrites an existing plugin, and
 * fails gracefully when the current user or filesystem cannot install plugins.
 *
 * @return true|WP_Error
 */
function vetra_install_bundled_plus_plugin() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return new WP_Error( 'vetra_plus_install_permission', __( 'مجوز نصب افزونه برای نصب خودکار Vetra Plus وجود ندارد.', 'vetra-portal-theme' ) );
	}

	$plugin_file = 'vetra-plus/vetra-plus.php';
	if ( function_exists( 'is_plugin_active' ) && is_plugin_active( $plugin_file ) ) {
		return true;
	}

	$source      = trailingslashit( get_template_directory() ) . 'vetra-plus';
	$destination = trailingslashit( WP_PLUGIN_DIR ) . 'vetra-plus';
	$entry_file  = trailingslashit( $destination ) . 'vetra-plus.php';

	if ( ! is_readable( trailingslashit( $source ) . 'vetra-plus.php' ) ) {
		return new WP_Error( 'vetra_plus_bundle_missing', __( 'بستهٔ داخلی Vetra Plus در پوسته پیدا نشد.', 'vetra-portal-theme' ) );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( ! file_exists( $entry_file ) ) {
		if ( ! WP_Filesystem() || ! wp_mkdir_p( $destination ) ) {
			return new WP_Error( 'vetra_plus_filesystem', __( 'دسترسی نوشتن برای نصب Vetra Plus فراهم نیست.', 'vetra-portal-theme' ) );
		}
		$result = copy_dir( $source, $destination );
		if ( is_wp_error( $result ) || ! file_exists( $entry_file ) ) {
			return is_wp_error( $result ) ? $result : new WP_Error( 'vetra_plus_copy_failed', __( 'کپی فایل‌های Vetra Plus انجام نشد.', 'vetra-portal-theme' ) );
		}
	}

	$result = activate_plugin( $plugin_file );
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return true;
}

/**
 * Install Vetra Plus after the theme has been switched on.
 */
function vetra_maybe_install_bundled_plus_plugin() {
	$result = vetra_install_bundled_plus_plugin();
	if ( is_wp_error( $result ) ) {
		update_option( 'vetra_plus_install_notice', $result->get_error_message(), false );
		return;
	}
	delete_option( 'vetra_plus_install_notice' );
}
add_action( 'after_switch_theme', 'vetra_maybe_install_bundled_plus_plugin', 20 );

/**
 * Show a recoverable admin notice if automatic installation was blocked.
 */
function vetra_bundled_plus_install_notice() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	$message = get_option( 'vetra_plus_install_notice', '' );
	if ( ! $message ) {
		return;
	}
	echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html( $message ) . ' ' . esc_html__( 'Vetra Plus را می‌توانید از بستهٔ افزونه یا بخش افزونه‌های وترا نصب کنید.', 'vetra-portal-theme' ) . '</p></div>';
}
add_action( 'admin_notices', 'vetra_bundled_plus_install_notice' );
