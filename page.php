<?php
/**
 * Standard WordPress page template.
 *
 * @package VetraPortal
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'vetra-content-card' ); ?>>
		<header class="vetra-entry-header">
			<h1><?php the_title(); ?></h1>
		</header>
		<div class="vetra-entry-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;
get_footer();
