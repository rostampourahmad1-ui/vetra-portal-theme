<?php
/**
 * Corporate site footer.
 *
 * @package VetraPortal
 */
?>
	</main>
	<?php $vetra_has_custom_footer = function_exists( 'vetra_render_layout' ) && vetra_render_layout( 'footer' ); ?>
	<?php if ( ! $vetra_has_custom_footer && vetra_option( 'footer_enabled', true ) ) : ?>
	<footer id="contact" class="vetra-footer">
		<div class="vetra-container">
			<div class="vetra-footer__top">
				<div class="vetra-footer__brand"><a class="vetra-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="vetra-brand__mark"><?php if ( vetra_brand_logo_url() ) : ?><img class="vetra-logo-light" src="<?php echo esc_url( vetra_brand_logo_url( 'light' ) ); ?>" alt=""><img class="vetra-logo-dark" src="<?php echo esc_url( vetra_brand_logo_url( 'dark' ) ); ?>" alt=""><?php else : echo vetra_inline_icon( 'building' ); endif; ?></span><span class="vetra-brand__copy"><strong><?php echo esc_html( vetra_option( 'brand_title' ) ); ?></strong><small><?php echo esc_html( vetra_option( 'brand_subtitle' ) ); ?></small></span></a><p><?php echo esc_html( vetra_option( 'footer_text' ) ); ?></p></div>
					<?php if ( vetra_option( 'footer_contact_enabled', true ) ) : ?><div class="vetra-footer__contact"><span><?php esc_html_e( 'ارتباط با وترا', 'vetra-portal' ); ?></span><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', vetra_option( 'contact_phone' ) ) ); ?>"><?php echo esc_html( vetra_option( 'contact_phone' ) ); ?></a><a href="mailto:<?php echo esc_attr( vetra_option( 'contact_email' ) ); ?>"><?php echo esc_html( vetra_option( 'contact_email' ) ); ?></a></div><?php endif; ?>
					<div class="vetra-footer__address"><span><?php esc_html_e( 'دفتر مرکزی', 'vetra-portal' ); ?></span><p><?php echo esc_html( vetra_option( 'contact_address' ) ); ?></p></div>
				</div>
				<?php if ( has_nav_menu( 'footer' ) ) : ?><nav class="vetra-footer__nav" aria-label="<?php esc_attr_e( 'ناوبری پابرگ', 'vetra-portal' ); ?>"><?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1 ) ); ?></nav><?php endif; ?>
				<?php if ( vetra_option( 'footer_bottom_enabled', true ) ) : ?><div class="vetra-footer__bottom"><span><?php echo esc_html( str_replace( '{year}', gmdate( 'Y' ), vetra_option( 'copyright_text', '© {year} گروه ساختمانی و مهندسی وترا' ) ) ); ?></span><span><?php esc_html_e( 'ساخته‌شده برای ماندگاری', 'vetra-portal' ); ?></span></div><?php endif; ?>
		</div>
	</footer>
	<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
