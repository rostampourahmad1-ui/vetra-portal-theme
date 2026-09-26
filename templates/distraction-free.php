<?php
/** Template Name: وترا: بدون حواس‌پرتی */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?><div class="vetra-distraction-free"><div class="vetra-container"><?php while ( have_posts() ) : the_post(); ?><article <?php post_class( 'vetra-content-card' ); ?>><h1><?php the_title(); ?></h1><div class="vetra-entry-content"><?php the_content(); ?></div></article><?php endwhile; ?></div></div><?php get_footer();
