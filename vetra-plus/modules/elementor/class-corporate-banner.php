<?php
/**
 * Vetra corporate banner widget.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class CorporateBanner extends \Elementor\Widget_Base {
	public function get_name(): string { return 'vetra-corporate-banner'; }
	public function get_title(): string { return esc_html__( 'Vetra Corporate Banner', 'vetra-plus' ); }
	public function get_icon(): string { return 'eicon-banner'; }
	public function get_categories(): array { return array( 'vetra-elements' ); }
	public function get_keywords(): array { return array( 'vetra', 'banner', 'corporate', 'architecture' ); }
	public function get_style_depends(): array { return array( 'vetra-plus-elements' ); }

	protected function register_controls(): void {
		$this->start_controls_section( 'content_section', array( 'label' => esc_html__( 'بنر شرکتی', 'vetra-plus' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'عنوان', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'ساختن آینده‌ای ماندگار', 'vetra-plus' ) ) );
		$this->add_control( 'text', array( 'label' => esc_html__( 'متن', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::TEXTAREA ) );
		$this->add_control( 'button_text', array( 'label' => esc_html__( 'متن دکمه', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::TEXT ) );
		$this->add_control( 'button_url', array( 'label' => esc_html__( 'پیوند دکمه', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => 'https://example.com' ) );
		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['button_url']['url'] ) ) {
			$settings['button_url'] = $settings['button_url']['url'];
		}
		echo Renderer::banner( $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- renderer escapes output.
	}
}
