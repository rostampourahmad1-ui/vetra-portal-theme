<?php
/**
 * Shared project archive template.
 *
 * @package VetraPortal
 */

get_header();
?>
<section class="vetra-page-hero vetra-container vetra-reveal vetra-page-hero--center">
	<span class="vetra-kicker"><i></i>پروژه‌های وترا</span>
	<h1>ساخته‌هایی با روایت ماندگار.</h1>
	<p>نمونه‌ای از پروژه‌های طراحی، مهندسی و اجرای گروه وترا.</p>
</section>
<section class="vetra-section vetra-container vetra-reveal">
	<?php if ( have_posts() ) : ?>
		<div class="vetra-project-grid vetra-project-grid--shortcode">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="vetra-project-card">
				<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'large' ); else : ?><div class="vetra-project-card__art vetra-project-card__art--1"><span><?php echo vetra_inline_icon( 'building' ); ?></span></div><?php endif; ?>
				<div class="vetra-project-card__overlay"><span><?php echo esc_html( get_post_meta( get_the_ID(), '_project_location', true ) ); ?></span><h3><?php the_title(); ?></h3><a href="<?php the_permalink(); ?>" aria-label="مشاهده پروژه"><?php echo vetra_inline_icon( 'arrow-up' ); ?></a></div>
			</article>
		<?php endwhile; ?>
		</div>
	<?php else : get_template_part( 'template-parts/empty', 'content' ); endif; ?>
</section>
<?php
get_footer();
