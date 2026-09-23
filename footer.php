<?php
/**
 * Footer and shell close.
 *
 * @package VetraPortal
 */
?>
		</main>
	</div>
	<footer class="vetra-footer">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> پرتال مدیریت ساخت وترا</span>
		<span><?php echo esc_html( vetra_option( 'footer_text' ) ); ?></span>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
