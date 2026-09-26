<?php
/**
 * 404 template for the corporate Vetra theme.
 *
 * @package VetraPortal
 */

get_header();
?>
<section class="vetra-empty-state" aria-labelledby="vetra-404-title">
	<span class="vetra-empty-state__icon"><?php echo vetra_inline_icon( 'file' ); ?></span>
	<h1 id="vetra-404-title"><?php esc_html_e( 'صفحه پیدا نشد', 'vetra-portal' ); ?></h1>
	<p><?php esc_html_e( 'نشانی موردنظر وجود ندارد یا پیوندهای یکتا هنوز به‌روزرسانی نشده‌اند.', 'vetra-portal' ); ?></p>
	<a class="vetra-button vetra-button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php esc_html_e( 'بازگشت به صفحه اصلی', 'vetra-portal' ); ?>
		<span><?php echo vetra_inline_icon( 'arrow' ); ?></span>
	</a>
</section>
<?php
get_footer();
