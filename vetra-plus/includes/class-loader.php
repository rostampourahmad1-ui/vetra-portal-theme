<?php
/**
 * Vetra Plus class loader.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Loader {
	public static function register() {
		static $registered = false;
		if ( $registered ) {
			return;
		}
		$registered = true;
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	public static function load( $class ) {
		$map = array(
			'Vetra\\Plus\\Plugin'       => 'class-plugin.php',
			'Vetra\\Plus\\Elementor\\Module' => '../modules/elementor/class-module.php',
			'Vetra\\Plus\\Elementor\\Renderer' => '../modules/elementor/class-renderer.php',
			'Vetra\\Plus\\Elementor\\CorporateBanner' => '../modules/elementor/class-corporate-banner.php',
			'Vetra\\Plus\\Settings\\SettingsSchema' => '../modules/settings/class-settings-schema.php',
			'Vetra\\Plus\\Settings\\Migration' => '../modules/settings/class-migration.php',
			'Vetra\\Plus\\Settings\\Settings' => '../modules/settings/class-settings.php',
		);
		if ( ! isset( $map[ $class ] ) ) {
			return;
		}
		$file = VETRA_PLUS_DIR . 'includes/' . $map[ $class ];
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
}
