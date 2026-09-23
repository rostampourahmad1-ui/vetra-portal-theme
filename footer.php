<?php
/**
 * Corporate site footer.
 *
 * @package VetraPortal
 */
?>
	</main>
	<footer id="contact" class="vetra-footer">
		<div class="vetra-container">
			<div class="vetra-footer__top">
				<div class="vetra-footer__brand"><a class="vetra-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="vetra-brand__mark"><?php echo vetra_brand_logo_url() ? '<img src="' . esc_url( vetra_brand_logo_url() ) . '" alt="" />' : vetra_inline_icon( 'building' ); ?></span><span class="vetra-brand__copy"><strong><?php echo esc_html( vetra_option( 'brand_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'brand_subtitle' ) ); ?></small></span></a><p><?php echo esc_html( vetra_option( 'footer_text' ) ); ?></p></div>
				<div class="vetra-footer__contact"><span>ارتباط با وترا</span><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', vetra_option( 'contact_phone' ) ) ); ?>"><?php echo esc_html( vetra_option( 'contact_phone' ) ); ?></a><a href="mailto:<?php echo esc_attr( vetra_option( 'contact_email' ) ); ?>"><?php echo esc_html( vetra_option( 'contact_email' ) ); ?></a></div>
				<div class="vetra-footer__address"><span>دفتر مرکزی</span><p><?php echo esc_html( vetra_option( 'contact_address' ) ); ?></p></div>
			</div>
			<div class="vetra-footer__bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> گروه ساختمانی و مهندسی وترا</span><span>ساخته‌شده برای ماندگاری</span></div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
