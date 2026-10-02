<?php
/**
 * Plugin lifecycle coordinator.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	private static $instance;

	public static function boot() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		register_activation_hook( VETRA_PLUS_FILE, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( VETRA_PLUS_FILE, array( __CLASS__, 'deactivate' ) );
		return self::$instance;
	}

	private function __construct() {
		new \Vetra\Plus\Elementor\Module();
		new \Vetra\Plus\Settings\Settings();
		add_action( 'vetra_theme_components_registered', array( $this, 'theme_adapter_ready' ), 20 );
	}

	public static function activate() {
		flush_rewrite_rules();
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}


	public function theme_adapter_ready() {
		do_action( 'vetra_plus_theme_adapter_ready' );
	}
}
