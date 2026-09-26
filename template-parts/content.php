<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<article <?php post_class( 'vetra-content-card' ); ?>>
	<header class="vetra-entry-header">
		<?php if ( is_singular() ) : ?><h1><?php the_title(); ?></h1><?php else : ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php endif; ?>
		<?php vetra_component_post_meta(); ?>
	</header>
	<?php if ( has_post_thumbnail() ) : ?><a class="vetra-entry-thumbnail" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></a><?php endif; ?>
	<div class="vetra-entry-content"><?php is_singular() ? the_content() : the_excerpt(); ?></div>
	<?php if ( ! is_singular() ) : ?><a class="vetra-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'ادامه مطلب', 'vetra-portal' ); ?></a><?php endif; ?>
	<?php if ( is_singular() ) : ?><div class="vetra-entry-taxonomy"><?php the_category( ', ' ); ?><?php the_tags( '<span class="vetra-tags">', ', ', '</span>' ); ?></div><?php vetra_component_share(); ?><nav class="vetra-post-navigation" aria-label="<?php esc_attr_e( 'ناوبری نوشته', 'vetra-portal' ); ?>"><?php the_post_navigation(); ?></nav><?php vetra_component_related(); endif; ?>
</article>
