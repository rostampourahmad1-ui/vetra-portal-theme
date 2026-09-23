<?php
/**
 * Template Name: خدمات
 *
 * @package VetraPortal
 */

get_header();
?>
<section class="vetra-page-hero vetra-container vetra-reveal vetra-page-hero--center">
	<span class="vetra-kicker"><i></i><?php esc_html_e( 'خدمات وترا', 'vetra-portal' ); ?></span>
	<h1><?php echo esc_html( vetra_option( 'services_title' ) ); ?></h1>
	<p><?php echo esc_html( vetra_option( 'services_intro' ) ); ?></p>
</section>

<section class="vetra-section vetra-container vetra-reveal">
	<div class="vetra-service-grid vetra-service-grid--large">
		<?php $icons = array( 'pen', 'chart', 'building' ); for ( $i = 1; $i <= 3; $i++ ) : ?>
		<article class="vetra-service-card">
			<span class="vetra-service-card__number">۰<?php echo esc_html( $i ); ?></span>
			<span class="vetra-service-card__icon"><?php echo vetra_inline_icon( $icons[ $i - 1 ] ); ?></span>
			<h3><?php echo esc_html( vetra_option( 'service_' . $i . '_title' ) ); ?></h3>
			<p><?php echo esc_html( vetra_option( 'service_' . $i . '_text' ) ); ?></p>
			<ul class="vetra-service-card__list">
				<li><?php echo vetra_inline_icon( 'check' ); ?> مشاوره تخصصی</li>
				<li><?php echo vetra_inline_icon( 'check' ); ?> طراحی تا اجرا</li>
				<li><?php echo vetra_inline_icon( 'check' ); ?> گزارش‌دهی منظم</li>
			</ul>
		</article>
		<?php endfor; ?>
	</div>
</section>

<section class="vetra-about vetra-container vetra-reveal">
	<div class="vetra-about__copy">
		<span class="vetra-kicker"><i></i>روند همکاری</span>
		<h2>از ایده تا تحویل، همراه شما هستیم.</h2>
		<p>فرآیند همکاری با وترا در پنج مرحله طراحی شده است: شناخت نیاز، طراحی مفهومی، مهندسی جزئیات، اجرای نظارت‌شده و تحویل نهایی با پشتیبانی.</p>
		<div class="vetra-check-list">
			<span><?php echo vetra_inline_icon( 'check' ); ?>جلسه مشاوره و بازدید</span>
			<span><?php echo vetra_inline_icon( 'check' ); ?>طراحی و تأیید نقشه‌ها</span>
			<span><?php echo vetra_inline_icon( 'check' ); ?>اجرا با نظارت مستمر</span>
		</div>
	</div>
	<div class="vetra-about__visual">
		<div class="vetra-about__art"><div></div><span>PROCESS<br><b>MAP</b></span></div>
		<span class="vetra-about__stamp">P</span>
	</div>
</section>
<?php
get_footer();
