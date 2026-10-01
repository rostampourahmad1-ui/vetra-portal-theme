<?php
/**
 * VETRA core class loader.
 *
 * The loader is intentionally small and explicit: only VETRA-owned classes are
 * mapped, so third-party namespaces and WordPress internals are never touched.
 *
 * @package VetraPortal
 */
namespace Vetra\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Loader {
	/**
	 * Register the VETRA-only autoloader once.
	 *
	 * @return void
	 */
	public static function register() {
		static $registered = false;

		if ( $registered ) {
			return;
		}

		$registered = true;
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	/**
	 * Load a known VETRA class.
	 *
	 * @param string $class Fully-qualified class name.
	 * @return void
	 */
	public static function load( $class ) {
		$map = array(
			'Vetra\\Theme\\Theme'         => 'class-theme.php',
			'Vetra\\Theme\\Admin\\Dashboard' => 'class-dashboard.php',
		);

		if ( ! isset( $map[ $class ] ) ) {
			return;
		}

		$base = defined( 'VETRA_PORTAL_DIR' ) ? VETRA_PORTAL_DIR . '/inc' : '';
		$file = $base . ( 'Vetra\\Theme\\Admin\\Dashboard' === $class ? '/admin/' : '/core/' ) . $map[ $class ];

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
}

Loader::register();
