<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<article <?php post_class( 'vetra-content-card' ); ?>>
	<header class="vetra-entry-header">
		<?php if ( is_singular() ) : ?><h1><?php the_title(); ?></h1><?php else : ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php endif; ?>
		<div class="vetra-entry-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time><?php if ( get_the_author() ) : ?> <span><?php echo esc_html( get_the_author() ); ?></span><?php endif; ?></div>
	</header>
	<?php if ( has_post_thumbnail() ) : ?><a class="vetra-entry-thumbnail" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></a><?php endif; ?>
	<div class="vetra-entry-content"><?php is_singular() ? the_content() : the_excerpt(); ?></div>
	<?php if ( ! is_singular() ) : ?><a class="vetra-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'ادامه مطلب', 'vetra-portal' ); ?></a><?php endif; ?>
</article>
