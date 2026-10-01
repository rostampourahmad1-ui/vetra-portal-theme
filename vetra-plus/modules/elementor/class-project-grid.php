<?php
/**
 * Vetra project grid widget.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ProjectGrid extends \Elementor\Widget_Base {
	public function get_name(): string { return 'vetra-project-grid'; }
	public function get_title(): string { return esc_html__( 'Vetra Project Grid', 'vetra-plus' ); }
	public function get_icon(): string { return 'eicon-posts-grid'; }
	public function get_categories(): array { return array( 'vetra-elements' ); }
	public function get_keywords(): array { return array( 'vetra', 'project', 'portfolio', 'grid' ); }
	public function get_style_depends(): array { return array( 'vetra-plus-elements' ); }

	protected function register_controls(): void {
		$this->start_controls_section( 'content_section', array( 'label' => esc_html__( 'محتوا', 'vetra-plus' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'count', array( 'label' => esc_html__( 'تعداد پروژه', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 6, 'min' => 1, 'max' => 24 ) );
		$this->add_control( 'category', array( 'label' => esc_html__( 'نامک دسته پروژه', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::TEXT, 'placeholder' => 'commercial' ) );
		$this->add_control( 'skill', array( 'label' => esc_html__( 'نامک تخصص', 'vetra-plus' ), 'type' => \Elementor\Controls_Manager::TEXT, 'placeholder' => 'architecture' ) );
		$this->end_controls_section();
	}

	protected function render(): void {
		echo Renderer::projects( $this->get_settings_for_display() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- renderer escapes output.
	}
}
