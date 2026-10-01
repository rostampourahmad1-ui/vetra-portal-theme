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
	public static function projects_shortcode( $atts = array() ) {
		self::enqueue_fallback_assets();
		$atts = shortcode_atts( array( 'count' => 6, 'category' => '', 'skill' => '' ), $atts, 'vetra_projects' );
		return self::projects( $atts );
	}

	public static function gallery_shortcode( $atts = array() ) {
		self::enqueue_fallback_assets();
		$atts = shortcode_atts( array( 'ids' => '' ), $atts, 'vetra_project_gallery' );
		return self::gallery( $atts );
	}

	public static function banner_shortcode( $atts = array() ) {
		self::enqueue_fallback_assets();
		$atts = shortcode_atts( array( 'title' => '', 'text' => '', 'button_url' => '', 'button_text' => '' ), $atts, 'vetra_corporate_banner' );
		return self::banner( $atts );
	}

	public static function projects( $settings ) {
		$count = min( 24, max( 1, absint( $settings['count'] ?? 6 ) ) );
		$query_args = array(
			'post_type' => 'vetra_project',
			'post_status' => 'publish',
			'posts_per_page' => $count,
			'no_found_rows' => true,
		);
		$tax_query = array();
		if ( ! empty( $settings['category'] ) ) {
			$tax_query[] = array( 'taxonomy' => 'vetra_project_category', 'field' => 'slug', 'terms' => sanitize_title( $settings['category'] ) );
		}
		if ( ! empty( $settings['skill'] ) ) {
			$tax_query[] = array( 'taxonomy' => 'vetra_project_skill', 'field' => 'slug', 'terms' => sanitize_title( $settings['skill'] ) );
		}
		if ( $tax_query ) {
			$query_args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query );
		}
		$query = new \WP_Query( $query_args );
		if ( ! $query->have_posts() ) {
			return '<div class="vetra-plus-empty" dir="rtl">' . esc_html__( 'پروژه‌ای برای نمایش پیدا نشد.', 'vetra-plus' ) . '</div>';
		}
		ob_start();
		?>
		<div class="vetra-plus-project-grid" dir="rtl" data-vetra-project-grid>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<article class="vetra-plus-project-card">
					<?php if ( has_post_thumbnail() ) : ?><a class="vetra-plus-project-card__image" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( get_post_thumbnail_id(), 'large', false, array( 'loading' => 'lazy' ) ); ?></a><?php endif; ?>
					<div class="vetra-plus-project-card__body">
						<h3><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ); ?></a></h3>
						<?php if ( get_the_excerpt() ) : ?><p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p><?php endif; ?>
						<a class="vetra-plus-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'مشاهده پروژه', 'vetra-plus' ); ?></a>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function gallery( $settings ) {
		$ids = self::attachment_ids( $settings['ids'] ?? '' );
		if ( ! $ids && get_the_ID() ) {
			$ids = self::attachment_ids( get_post_meta( get_the_ID(), '_vetra_project_gallery', true ) );
		}
		if ( ! $ids ) {
			return '<div class="vetra-plus-empty" dir="rtl">' . esc_html__( 'تصویری برای نمایش انتخاب نشده است.', 'vetra-plus' ) . '</div>';
		}
		ob_start();
		?>
		<div class="vetra-plus-gallery" dir="rtl" data-vetra-gallery>
			<?php foreach ( $ids as $index => $id ) : ?>
				<figure class="vetra-plus-gallery__item"><a href="<?php echo esc_url( wp_get_attachment_image_url( $id, 'full' ) ); ?>" data-vetra-gallery-link aria-label="<?php echo esc_attr( sprintf( __( 'نمایش تصویر %d', 'vetra-plus' ), $index + 1 ) ); ?>"><?php echo wp_get_attachment_image( $id, 'large', false, array( 'loading' => $index ? 'lazy' : 'eager' ) ); ?></a></figure>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
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

	private static function attachment_ids( $value ) {
		$values = is_array( $value ) ? $value : preg_split( '/[,\s]+/', (string) $value );
		return array_values( array_unique( array_filter( array_map( 'absint', $values ) ) ) );
	}

	private static function enqueue_fallback_assets() {
		wp_enqueue_style( 'vetra-plus-elements' );
		wp_enqueue_script( 'vetra-plus-elements' );
	}
}
