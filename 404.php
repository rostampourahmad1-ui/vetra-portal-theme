<?php
get_header();
?>
<section class="vetra-empty-state" aria-labelledby="vetra-404-title">
	<span class="vetra-form-icon is-large"><?php echo vetra_inline_icon( 'file' ); ?></span>
	<h1 id="vetra-404-title">صفحه پیدا نشد</h1>
	<p>نشانی موردنظر وجود ندارد یا پیوندهای یکتا هنوز به‌روزرسانی نشده‌اند.</p>
	<a class="vetra-outline-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">بازگشت به مرکز فرم‌ها</a>
</section>
<?php
get_footer();
