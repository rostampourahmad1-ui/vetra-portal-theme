<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<div class="vetra-container vetra-content-layout"><section class="vetra-content-area" aria-labelledby="archive-title">
	<?php vetra_component_breadcrumbs(); ?>
	<header class="vetra-entry-header"><h1 id="archive-title"><?php the_archive_title(); ?></h1><?php the_archive_description( '<div class="vetra-entry-content">', '</div>' ); ?></header>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', get_post_type() ); endwhile; the_posts_pagination(); else : get_template_part( 'template-parts/empty', 'content' ); endif; ?>
</section><?php get_sidebar(); ?></div>
<?php get_footer();
