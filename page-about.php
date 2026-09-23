<?php
/**
 * Template Name: درباره ما
 *
 * @package VetraPortal
 */

get_header();
?>
<section class="vetra-page-hero vetra-container vetra-reveal">
	<div class="vetra-page-hero__copy">
		<span class="vetra-kicker"><i></i><?php esc_html_e( 'درباره وترا', 'vetra-portal' ); ?></span>
		<h1><?php echo esc_html( vetra_option( 'about_title' ) ); ?></h1>
		<p><?php echo esc_html( vetra_option( 'about_text' ) ); ?></p>
	</div>
	<div class="vetra-page-hero__visual">
		<?php if ( vetra_image_url( 'about_image' ) ) : ?>
			<img src="<?php echo esc_url( vetra_image_url( 'about_image' ) ); ?>" alt="" />
		<?php else : ?>
			<div class="vetra-about__art"><div></div><span>VETRA<br><b>GROUP</b></span></div>
		<?php endif; ?>
	</div>
</section>

<section class="vetra-section vetra-container vetra-reveal">
	<div class="vetra-section-heading"><div><span class="vetra-kicker"><i></i>ارزش‌های ما</span><h2>چرا وترا؟</h2></div></div>
	<div class="vetra-service-grid">
		<article class="vetra-service-card">
			<span class="vetra-service-card__number">۰۱</span>
			<span class="vetra-service-card__icon"><?php echo vetra_inline_icon( 'pen' ); ?></span>
			<h3>معماری ماندگار</h3>
			<p>طراحی با نگاهی بلندمدت به زیبایی، کارایی و هماهنگی با محیط.</p>
		</article>
		<article class="vetra-service-card">
			<span class="vetra-service-card__number">۰۲</span>
			<span class="vetra-service-card__icon"><?php echo vetra_inline_icon( 'chart' ); ?></span>
			<h3>مدیریت شفاف</h3>
			<p>برنامه‌ریزی دقیق زمان و هزینه با گزارش‌دهی شفاف در هر مرحله.</p>
		</article>
		<article class="vetra-service-card">
			<span class="vetra-service-card__number">۰۳</span>
			<span class="vetra-service-card__icon"><?php echo vetra_inline_icon( 'building' ); ?></span>
			<h3>اجرا با کیفیت</h3>
			<p>نظارت فنی حرفه‌ای و استانداردهای اجرایی بالا در تمامی پروژه‌ها.</p>
		</article>
	</div>
</section>

<section class="vetra-cta vetra-container vetra-reveal">
	<div><span class="vetra-kicker"><i></i>همراه آینده</span><h2>آماده شروع پروژه بعدی شما هستیم.</h2><p>با تیم وترا در تماس باشید تا مسیر ساخت را با هم طراحی کنیم.</p></div>
	<a class="vetra-button vetra-button--light" href="<?php echo esc_url( vetra_option( 'cta_button_url' ) ); ?>"><?php echo esc_html( vetra_option( 'cta_button_text' ) ); ?><span><?php echo vetra_inline_icon( 'arrow-up' ); ?></span></a>
</section>
<?php
get_footer();
