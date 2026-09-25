<?php
/**
 * Corporate site footer.
 *
 * @package VetraPortal
 */
?>
	</main>
	<?php if ( vetra_option( 'footer_enabled', true ) ) : ?>
	<footer id="contact" class="vetra-footer">
		<div class="vetra-container">
			<div class="vetra-footer__top">
				<div class="vetra-footer__brand"><a class="vetra-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="vetra-brand__mark"><?php if ( vetra_brand_logo_url() ) : ?><img class="vetra-logo-light" src="<?php echo esc_url( vetra_brand_logo_url( 'light' ) ); ?>" alt=""><img class="vetra-logo-dark" src="<?php echo esc_url( vetra_brand_logo_url( 'dark' ) ); ?>" alt=""><?php else : echo vetra_inline_icon( 'building' ); endif; ?></span><span class="vetra-brand__copy"><strong><?php echo esc_html( vetra_option( 'brand_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'brand_subtitle' ) ); ?></small></span></a><p><?php echo esc_html( vetra_option( 'footer_text' ) ); ?></p></div>
				<?php if ( vetra_option( 'footer_contact_enabled', true ) ) : ?><div class="vetra-footer__contact"><span>ارتباط با وترا</span><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', vetra_option( 'contact_phone' ) ) ); ?>"><?php echo esc_html( vetra_option( 'contact_phone' ) ); ?></a><a href="mailto:<?php echo esc_attr( vetra_option( 'contact_email' ) ); ?>"><?php echo esc_html( vetra_option( 'contact_email' ) ); ?></a></div><?php endif; ?>
				<div class="vetra-footer__address"><span>دفتر مرکزی</span><p><?php echo esc_html( vetra_option( 'contact_address' ) ); ?></p></div>
			</div>
			<?php if ( vetra_option( 'footer_bottom_enabled', true ) ) : ?><div class="vetra-footer__bottom"><span><?php echo esc_html( str_replace( '{year}', gmdate( 'Y' ), vetra_option( 'copyright_text', '© {year} گروه ساختمانی و مهندسی وترا' ) ) ); ?></span><span>ساخته‌شده برای ماندگاری</span></div><?php endif; ?>
		</div>
	</footer>
	<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
