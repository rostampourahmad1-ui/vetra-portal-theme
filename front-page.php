<?php
get_header();
?>
<?php if ( vetra_option( 'home_hero_enabled', true ) ) : ?>
<section class="vetra-hero vetra-container vetra-reveal" aria-labelledby="vetra-hero-title">
	<div class="vetra-hero__copy">
		<span class="vetra-kicker"><i></i><?php echo esc_html( vetra_option( 'hero_eyebrow' ) ); ?></span>
		<h1 id="vetra-hero-title"><?php echo wp_kses_post( nl2br( esc_html( vetra_option( 'hero_title' ) ) ) ); ?></h1>
		<p><?php echo esc_html( vetra_option( 'hero_description' ) ); ?></p>
		<div class="vetra-hero__actions"><a class="vetra-button vetra-button--primary" href="<?php echo esc_url( vetra_option( 'hero_primary_url' ) ); ?>"><?php echo esc_html( vetra_option( 'hero_primary_text' ) ); ?><span><?php echo vetra_inline_icon( 'arrow-up' ); ?></span></a><a class="vetra-button vetra-button--quiet" href="<?php echo esc_url( vetra_option( 'hero_secondary_url' ) ); ?>"><?php echo esc_html( vetra_option( 'hero_secondary_text' ) ); ?><span><?php echo vetra_inline_icon( 'arrow' ); ?></span></a></div>
			<div class="vetra-hero__signature"><span class="vetra-avatars" aria-hidden="true"><i></i><i></i><i></i></span><span><strong><?php esc_html_e( 'همراه پروژه‌های ماندگار', 'vetra-portal-theme' ); ?></strong><small><?php esc_html_e( 'از ایده تا تحویل نهایی', 'vetra-portal-theme' ); ?></small></span></div>
	</div>
	<div class="vetra-hero__visual">
		<?php if ( vetra_image_url( 'hero_image' ) ) : ?><img class="vetra-hero__image" src="<?php echo esc_url( vetra_image_url( 'hero_image' ) ); ?>" alt="" /><?php endif; ?>
		<div class="vetra-hero__grid"></div><img class="vetra-hero__building-img" src="<?php echo esc_url( VETRA_PORTAL_URI . '/assets/images/art-building.png' ); ?>" alt="" loading="lazy" />
			<div class="vetra-float-card vetra-float-card--top"><span><?php echo vetra_inline_icon( 'chart' ); ?></span><small><?php esc_html_e( 'تحویل به‌موقع پروژه‌ها', 'vetra-portal-theme' ); ?></small><strong>۹۶٪</strong></div>
			<div class="vetra-float-card vetra-float-card--bottom"><small><?php esc_html_e( 'پروژه منتخب', 'vetra-portal-theme' ); ?></small><strong><?php esc_html_e( 'ساخت با نگاه آینده', 'vetra-portal-theme' ); ?></strong><span><?php echo vetra_inline_icon( 'arrow-up' ); ?></span></div>
	</div>
</section>
<?php endif; ?>

<?php if ( vetra_option( 'home_stats_enabled', true ) ) : ?>
<section class="vetra-stat-strip vetra-container vetra-reveal" aria-label="<?php esc_attr_e( 'آمار وترا', 'vetra-portal-theme' ); ?>">
	<?php for ( $i = 1; $i <= 4; $i++ ) : ?><div class="vetra-stat"><strong><?php echo esc_html( vetra_option( 'stat_' . $i . '_number' ) ); ?></strong><span><?php echo esc_html( vetra_option( 'stat_' . $i . '_label' ) ); ?></span></div><?php endfor; ?>
</section>
<?php endif; ?>

<?php if ( vetra_option( 'home_services_enabled', true ) ) : ?>
<section id="services" class="vetra-section vetra-container vetra-reveal">
		<div class="vetra-section-heading"><div><span class="vetra-kicker"><i></i><?php esc_html_e( 'توانمندی‌های ما', 'vetra-portal-theme' ); ?></span><h2><?php echo esc_html( vetra_option( 'services_title' ) ); ?></h2></div><p><?php echo esc_html( vetra_option( 'services_intro' ) ); ?></p></div>
	<div class="vetra-service-grid">
			<?php $service_icons = array( 'pen', 'chart', 'building' ); for ( $i = 1; $i <= 3; $i++ ) : ?><article class="vetra-service-card"><span class="vetra-service-card__number">۰<?php echo esc_html( $i ); ?></span><span class="vetra-service-card__icon"><?php echo vetra_inline_icon( $service_icons[ $i - 1 ] ); ?></span><h3><?php echo esc_html( vetra_option( 'service_' . $i . '_title' ) ); ?></h3><p><?php echo esc_html( vetra_option( 'service_' . $i . '_text' ) ); ?></p><a href="#contact" aria-label="<?php echo esc_attr( vetra_option( 'service_' . $i . '_title' ) ); ?>"><?php esc_html_e( 'بیشتر بدانید', 'vetra-portal-theme' ); ?> <span><?php echo vetra_inline_icon( 'arrow' ); ?></span></a></article><?php endfor; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( vetra_option( 'home_about_enabled', true ) ) : ?>
<section id="about" class="vetra-about vetra-container vetra-reveal">
	<div class="vetra-about__visual"><?php if ( vetra_image_url( 'about_image' ) ) : ?><img src="<?php echo esc_url( vetra_image_url( 'about_image' ) ); ?>" alt="" loading="lazy" /><?php else : ?><img src="<?php echo esc_url( VETRA_PORTAL_URI . '/assets/images/art-interior.png' ); ?>" alt="" loading="lazy" /><?php endif; ?><span class="vetra-about__stamp">V</span></div>
	<div class="vetra-about__copy"><span class="vetra-kicker"><i></i>درباره وترا</span><h2><?php echo esc_html( vetra_option( 'about_title' ) ); ?></h2><p><?php echo esc_html( vetra_option( 'about_text' ) ); ?></p><div class="vetra-check-list"><span><?php echo vetra_inline_icon( 'check' ); ?>شفافیت در تصمیم‌گیری</span><span><?php echo vetra_inline_icon( 'check' ); ?>تعهد به کیفیت اجرا</span><span><?php echo vetra_inline_icon( 'check' ); ?>نگاه بلندمدت به ارزش پروژه</span></div><a class="vetra-text-button" href="#contact">با ما آشنا شوید <span><?php echo vetra_inline_icon( 'arrow' ); ?></span></a></div>
</section>
<?php endif; ?>

<?php if ( vetra_option( 'home_projects_enabled', true ) ) : ?>
<section id="projects" class="vetra-section vetra-container vetra-reveal">
		<div class="vetra-section-heading vetra-section-heading--projects"><div><span class="vetra-kicker"><i></i><?php esc_html_e( 'منتخب پروژه‌ها', 'vetra-portal-theme' ); ?></span><h2><?php echo esc_html( vetra_option( 'projects_title' ) ); ?></h2></div><p><?php echo esc_html( vetra_option( 'projects_intro' ) ); ?></p></div>
	<?php
	$featured_projects = vetra_featured_projects_get( array( 'number' => apply_filters( 'vetra_featured_projects_count', 6 ) ) );
	if ( ! empty( $featured_projects ) ) :
		?>
		<div class="vetra-project-grid vetra-project-grid--featured">
			<?php foreach ( $featured_projects as $featured_project ) : ?>
				<?php echo vetra_featured_project_card( $featured_project ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- card helper escapes. ?>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<?php
		$fallback_projects = vetra_featured_projects_fallback();
		if ( '' !== $fallback_projects ) :
			echo '<div class="vetra-project-grid">' . $fallback_projects . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fallback helper escapes.
		else :
			?>
			<div class="vetra-empty" role="status">
				<svg class="vetra-empty__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" aria-hidden="true"><path d="M4 7h16v12H4zM4 7l3-3h10l3 3"/></svg>
				<p class="vetra-empty__title"><?php esc_html_e( 'هنوز پروژه‌ای ثبت نشده است', 'vetra-portal-theme' ); ?></p>
				<p class="vetra-empty__text"><?php esc_html_e( 'به‌زودی پروژه‌های منتخب وترا در این بخش نمایش داده می‌شود.', 'vetra-portal-theme' ); ?></p>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</section>
<?php endif; ?>

<?php if ( vetra_option( 'home_cta_enabled', true ) ) : ?>
<section class="vetra-cta vetra-container vetra-reveal">
		<div><span class="vetra-kicker"><i></i><?php esc_html_e( 'همراه آینده', 'vetra-portal-theme' ); ?></span><h2><?php echo esc_html( vetra_option( 'cta_title' ) ); ?></h2><p><?php echo esc_html( vetra_option( 'cta_text' ) ); ?></p></div><a class="vetra-button vetra-button--light" href="<?php echo esc_url( vetra_option( 'cta_button_url' ) ); ?>"><?php echo esc_html( vetra_option( 'cta_button_text' ) ); ?><span><?php echo vetra_inline_icon( 'arrow-up' ); ?></span></a>
</section>
<?php endif; ?>
<?php
get_footer();
