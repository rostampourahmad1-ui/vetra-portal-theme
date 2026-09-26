<?php
/** Template Name: وترا: صفحه فرود */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?><div class="vetra-landing"><div class="vetra-container"><header class="vetra-page-hero vetra-page-hero--center"><span class="vetra-kicker"><i></i><?php esc_html_e( 'وترا', 'vetra-portal' ); ?></span><h1><?php the_title(); ?></h1></header><?php while ( have_posts() ) : the_post(); ?><div class="vetra-entry-content"><?php the_content(); ?></div><?php endwhile; ?></div></div><?php get_footer();
