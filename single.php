<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<div class="vetra-container vetra-content-layout"><section class="vetra-content-area">
	<?php vetra_component_breadcrumbs(); ?>
	<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', get_post_type() ); if ( get_the_author_meta( 'description' ) ) : ?><section class="vetra-author-box"><h2><?php esc_html_e( 'درباره نویسنده', 'vetra-portal' ); ?></h2><p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p></section><?php endif; comments_template(); endwhile; ?>
</section><?php get_sidebar(); ?></div>
<?php get_footer();
