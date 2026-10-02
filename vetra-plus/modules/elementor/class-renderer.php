<?php
/**
 * Shared renderers for Elementor widgets and Vetra Plus shortcodes.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Renderer {
	public static function banner_shortcode( $atts = array() ) {
		self::enqueue_fallback_assets();
		$atts = shortcode_atts( array( 'title' => '', 'text' => '', 'button_url' => '', 'button_text' => '' ), $atts, 'vetra_corporate_banner' );
		return self::banner( $atts );
	}

	public static function banner( $settings ) {
		$title = sanitize_text_field( $settings['title'] ?? '' );
		$text = sanitize_textarea_field( $settings['text'] ?? '' );
		$url = ! empty( $settings['button_url'] ) ? esc_url( $settings['button_url'] ) : '';
		$button = sanitize_text_field( $settings['button_text'] ?? '' );
		if ( ! $title && ! $text ) {
			return '';
		}
		ob_start();
		?>
		<section class="vetra-plus-banner" dir="rtl">
			<div class="vetra-plus-banner__content">
				<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
				<?php if ( $text ) : ?><p><?php echo esc_html( $text ); ?></p><?php endif; ?>
				<?php if ( $url && $button ) : ?><a class="vetra-plus-button" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $button ); ?></a><?php endif; ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}

	private static function enqueue_fallback_assets() {
		wp_enqueue_style( 'vetra-plus-elements' );
		wp_enqueue_script( 'vetra-plus-elements' );
	}
}
