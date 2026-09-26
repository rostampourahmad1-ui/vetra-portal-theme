<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<div class="vetra-container vetra-content-layout"><section class="vetra-content-area" aria-labelledby="search-title">
	<header class="vetra-entry-header"><h1 id="search-title"><?php printf( esc_html__( 'نتایج جست‌وجو برای: %s', 'vetra-portal' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1></header>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', get_post_type() ); endwhile; the_posts_pagination(); else : get_template_part( 'template-parts/empty', 'content' ); endif; ?>
</section><?php get_sidebar(); ?></div>
<?php get_footer();
