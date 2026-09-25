<?php
/**
 * Template Name: وترا: تمام‌عرض
 * Template Post Type: page
 *
 * @package VetraPortal
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'vetra-content-card vetra-content-card--full-width' ); ?>>
		<div class="vetra-entry-content"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;
get_footer();
