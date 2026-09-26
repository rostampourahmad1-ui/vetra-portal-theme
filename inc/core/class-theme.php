<?php
namespace Vetra\Theme;
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Theme {
	private static $instance;
	public static function boot() { if ( ! self::$instance ) { self::$instance = new self(); } return self::$instance; }
	private function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ), 5 );
		add_action( 'init', array( $this, 'register_components' ), 20 );
	}
	public function setup() {
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-spacing' );
	}
	public function register_components() {
		do_action( 'vetra_theme_components_registered' );
	}
}
