<?php
get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<article class="vetra-content-card">
			<h1><?php the_title(); ?></h1>
			<div class="vetra-entry-content"><?php the_content(); ?></div>
		</article>
		<?php
	endwhile;
else :
	get_template_part( 'template-parts/empty', 'content' );
endif;
get_footer();
