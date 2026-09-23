<?php
/**
 * Forms archive content.
 *
 * @package VetraPortal
 */

$terms = get_terms( array( 'taxonomy' => 'form_category', 'hide_empty' => true ) );
?>
<section class="vetra-forms-page" aria-labelledby="vetra-forms-title">
	<div class="vetra-forms-hero">
		<div class="vetra-forms-hero__icon"><?php echo vetra_inline_icon( 'file' ); ?></div>
		<div><span class="vetra-eyebrow"><span class="vetra-live-indicator"></span> دسترسی عملیاتی</span><h1 id="vetra-forms-title"><?php echo esc_html( vetra_option( 'forms_title' ) ); ?></h1><p><?php echo esc_html( vetra_option( 'forms_subtitle' ) ); ?></p></div>
		<label class="vetra-forms-search" for="vetra-form-search"><span><?php echo vetra_inline_icon( 'file' ); ?></span><input id="vetra-form-search" class="js-vetra-form-search" type="search" placeholder="<?php echo esc_attr( vetra_option( 'forms_search_placeholder' ) ); ?>" autocomplete="off"></label>
	</div>

	<nav class="vetra-form-filters" aria-label="فیلتر دسته‌بندی فرم‌ها">
		<button class="vetra-filter-button is-active js-vetra-filter" type="button" data-filter="all" aria-pressed="true">همه فرم‌ها <b><?php echo esc_html( wp_count_posts( 'vetra_form' )->publish ); ?></b></button>
		<?php if ( ! is_wp_error( $terms ) ) : foreach ( $terms as $term ) : ?>
			<button class="vetra-filter-button js-vetra-filter" type="button" data-filter="<?php echo esc_attr( $term->slug ); ?>" aria-pressed="false"><?php echo esc_html( $term->name ); ?> <b><?php echo esc_html( $term->count ); ?></b></button>
		<?php endforeach; endif; ?>
	</nav>

	<div class="vetra-forms-grid" data-vetra-forms-grid>
		<?php
		$forms = new WP_Query(
			array(
				'post_type'      => 'vetra_form',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array( 'meta_value_num' => 'ASC', 'date' => 'DESC' ),
				'meta_key'       => 'form_order',
			)
		);
		if ( $forms->have_posts() ) :
			while ( $forms->have_posts() ) :
				$forms->the_post();
				get_template_part( 'template-parts/form', 'card' );
			endwhile;
			wp_reset_postdata();
		else :
			get_template_part( 'template-parts/empty', 'forms' );
		endif;
		?>
	</div>
	<div class="vetra-no-results js-vetra-no-results" hidden><?php esc_html_e( 'فرمی با این مشخصات پیدا نشد.', 'vetra-portal' ); ?></div>
</section>
