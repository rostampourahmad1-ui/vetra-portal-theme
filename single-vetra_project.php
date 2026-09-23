<?php
/**
 * Shared project detail template.
 *
 * @package VetraPortal
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'vetra-project-single vetra-container vetra-reveal' ); ?>>
		<header class="vetra-project-single__header">
			<span class="vetra-kicker"><i></i>پروژه وترا</span>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		</header>
		<?php if ( has_post_thumbnail() ) : ?><div class="vetra-project-single__image"><?php the_post_thumbnail( 'full' ); ?></div><?php endif; ?>
		<div class="vetra-project-single__facts">
			<?php foreach ( array( 'project_client' => 'کارفرما', 'project_location' => 'موقعیت', 'project_type' => 'نوع پروژه', 'project_year' => 'سال اجرا', 'project_status' => 'وضعیت', 'project_area' => 'مساحت', 'project_completion' => 'تاریخ تحویل' ) as $key => $label ) : $value = get_post_meta( get_the_ID(), '_' . $key, true ); if ( $value ) : ?>
				<div><span><?php echo esc_html( $label ); ?></span><strong><?php echo esc_html( $value ); ?></strong></div>
			<?php endif; endforeach; ?>
		</div>
		<div class="vetra-entry-content"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;
get_footer();
