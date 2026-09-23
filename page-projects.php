<?php
/**
 * Template Name: پروژه‌ها
 *
 * @package VetraPortal
 */

get_header();
?>
<section class="vetra-page-hero vetra-container vetra-reveal vetra-page-hero--center">
	<span class="vetra-kicker"><i></i><?php esc_html_e( 'پروژه‌های وترا', 'vetra-portal' ); ?></span>
	<h1><?php echo esc_html( vetra_option( 'projects_title' ) ); ?></h1>
	<p><?php echo esc_html( vetra_option( 'projects_intro' ) ); ?></p>
</section>

<section class="vetra-section vetra-container vetra-reveal">
	<div class="vetra-project-grid vetra-project-grid--page">
		<?php for ( $i = 1; $i <= 3; $i++ ) : $image = vetra_image_url( 'project_' . $i . '_image' ); ?>
		<article class="vetra-project-card<?php echo 2 === $i ? ' vetra-project-card--large' : ''; ?>">
			<?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" /><?php else : ?>
			<div class="vetra-project-card__art vetra-project-card__art--<?php echo esc_attr( $i ); ?>"><span><?php echo vetra_inline_icon( 'building' ); ?></span></div>
			<?php endif; ?>
			<div class="vetra-project-card__overlay">
				<span><?php echo esc_html( vetra_option( 'project_' . $i . '_meta' ) ); ?></span>
				<h3><?php echo esc_html( vetra_option( 'project_' . $i . '_title' ) ); ?></h3>
				<a href="#contact" aria-label="<?php echo esc_attr( vetra_option( 'project_' . $i . '_title' ) ); ?>"><?php echo vetra_inline_icon( 'arrow-up' ); ?></a>
			</div>
		</article>
		<?php endfor; ?>
	</div>
</section>

<section class="vetra-cta vetra-container vetra-reveal">
	<div><span class="vetra-kicker"><i></i>پروژه جدید</span><h2>پروژه بعدی شما در دسترس است.</h2><p>برای مشاوره اولیه رایگان با ما تماس بگیرید.</p></div>
	<a class="vetra-button vetra-button--light" href="<?php echo esc_url( vetra_option( 'cta_button_url' ) ); ?>"><?php echo esc_html( vetra_option( 'cta_button_text' ) ); ?><span><?php echo vetra_inline_icon( 'arrow-up' ); ?></span></a>
</section>
<?php
get_footer();
