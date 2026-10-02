<?php
/**
 * Vetra Plus Elementor integration.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Module {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 5 );
		add_action( 'init', array( $this, 'register_shortcodes' ), 20 );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	public function register_assets() {
		$elements_css = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'vetra-elements.css' : 'vetra-elements.min.css';
		$elements_js = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'vetra-elements.js' : 'vetra-elements.min.js';
		wp_register_style( 'vetra-plus-elements', VETRA_PLUS_URL . 'assets/css/' . $elements_css, array(), VETRA_PLUS_VERSION );
		wp_register_script( 'vetra-plus-elements', VETRA_PLUS_URL . 'assets/js/' . $elements_js, array(), VETRA_PLUS_VERSION, true );
		wp_script_add_data( 'vetra-plus-elements', 'strategy', 'defer' );
	}

	public function register_shortcodes() {
		add_shortcode( 'vetra_corporate_banner', array( Renderer::class, 'banner_shortcode' ) );
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'vetra-elements',
			array(
				'title' => esc_html__( 'Vetra Elements', 'vetra-plus' ),
				'icon' => 'eicon-building',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		$widgets_manager->register( new CorporateBanner() );
	}
}
