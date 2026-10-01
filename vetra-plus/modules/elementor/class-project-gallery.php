<?php
/**
 * Vetra project gallery widget.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ProjectGallery extends \Elementor\Widget_Base {
	public function get_name(): string { return 'vetra-project-gallery'; }
	public function get_title(): string { return esc_html__( 'Vetra Project Gallery', 'vetra-plus' ); }
	public function get_icon(): string { return 'eicon-gallery-grid'; }
	public function get_categories(): array { return array( 'vetra-elements' ); }
	public function get_keywords(): array { return array( 'vetra', 'gallery', 'project', 'images' ); }
	public function get_style_depends(): array { return array( 'vetra-plus-elements' ); }
	public function get_script_depends(): array { return array( 'vetra-plus-elements' ); }

	protected function register_controls(): void {
		$this->start_controls_section( 'content_section', array( 'label' => esc_html__( 'گالری', 'vetra-plus' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'ids', array( 'label' => esc_html__( 'شناسه تصاویر', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::TEXT, 'description' => esc_html__( 'شناسه attachmentها را با کاما جدا کنید. در صفحه پروژه، مقدار خالی از متای گالری خوانده می‌شود.', 'vetra-plus' ) ) );
		$this->end_controls_section();
	}

	protected function render(): void {
		echo Renderer::gallery( $this->get_settings_for_display() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- renderer escapes output.
	}
}
