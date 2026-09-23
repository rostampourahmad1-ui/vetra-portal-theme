<?php
/**
 * Template Name: تماس با ما
 *
 * @package VetraPortal
 */

get_header();
?>
<section class="vetra-page-hero vetra-container vetra-reveal vetra-page-hero--center">
	<span class="vetra-kicker"><i></i><?php esc_html_e( 'تماس با ما', 'vetra-portal' ); ?></span>
	<h1>با وترا در ارتباط باشید.</h1>
	<p>برای هرگونه سوال، مشاوره یا شروع پروژه از طریق راه‌های زیر با ما تماس بگیرید.</p>
</section>

<section class="vetra-contact-grid vetra-container vetra-reveal">
	<div class="vetra-contact-card">
		<span class="vetra-contact-card__icon"><?php echo vetra_inline_icon( 'building' ); ?></span>
		<h3>دفتر مرکزی</h3>
		<p><?php echo esc_html( vetra_option( 'contact_address' ) ); ?></p>
	</div>
	<div class="vetra-contact-card">
		<span class="vetra-contact-card__icon"><?php echo vetra_inline_icon( 'arrow-up' ); ?></span>
		<h3>تلفن</h3>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', vetra_option( 'contact_phone' ) ) ); ?>"><?php echo esc_html( vetra_option( 'contact_phone' ) ); ?></a>
	</div>
	<div class="vetra-contact-card">
		<span class="vetra-contact-card__icon"><?php echo vetra_inline_icon( 'arrow' ); ?></span>
		<h3>ایمیل</h3>
		<a href="mailto:<?php echo esc_attr( vetra_option( 'contact_email' ) ); ?>"><?php echo esc_html( vetra_option( 'contact_email' ) ); ?></a>
	</div>
</section>

<section class="vetra-form-section vetra-container vetra-reveal">
	<div class="vetra-form-section__copy">
		<h2>فرم درخواست مشاوره</h2>
		<p>اطلاعات خود را ثبت کنید تا کارشناسان وترا در اسرع وقت با شما تماس بگیرند.</p>
	</div>
	<div class="vetra-form-section__box">
		<?php
		if ( function_exists( 'gravity_form' ) ) {
			gravity_form( 1, false, false, false, '', true );
		} elseif ( shortcode_exists( 'gravityform' ) ) {
			echo do_shortcode( '[gravityform id="1" title="false" description="false" ajax="true"]' );
		} else {
			?><p class="vetra-form-placeholder">لطفاً افزونه Gravity Forms را نصب و یک فرم با شناسه ۱ بسازید.</p><?php
		}
		?>
	</div>
</section>
<?php
get_footer();
