<?php
/**
 * Shared project detail template.
 *
 * @package VetraPortal
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'vetra-project-single vetra-container vetra-reveal' ); ?>>
		<header class="vetra-project-single__header">
			<span class="vetra-kicker"><i></i>پروژه وترا</span>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		</header>
		<?php if ( has_post_thumbnail() ) : ?><div class="vetra-project-single__image"><?php the_post_thumbnail( 'full' ); ?></div><?php endif; ?>
		<div class="vetra-project-single__facts">
			<?php foreach ( array( 'project_client' => 'نام کارفرما', 'project_usage' => 'کاربری', 'project_contract_type' => 'نوع قرارداد', 'project_contract_number' => 'شماره قرارداد', 'project_contractor' => 'پیمانکار', 'project_contract_start' => 'تاریخ شروع قرارداد', 'project_contract_duration' => 'مدت قرارداد', 'project_initial_amount' => 'مبلغ اولیه پیمان', 'project_supervisor' => 'ناظر پروژه', 'project_manager' => 'مدیر پروژه', 'project_site_supervisor' => 'سرپرست کارگاه', 'project_address' => 'آدرس پروژه', 'project_client_address' => 'آدرس کارفرما', 'project_urban_file' => 'شماره پرونده شهرسازی', 'project_registry_sub' => 'پلاک ثبتی فرعی', 'project_registry_main' => 'پلاک ثبتی اصلی' ) as $key => $label ) : $value = get_post_meta( get_the_ID(), '_' . $key, true ); if ( $value ) : ?>
				<div><span><?php echo esc_html( $label ); ?></span><strong><?php echo esc_html( $value ); ?></strong></div>
			<?php endif; endforeach; ?>
		</div>
		<div class="vetra-entry-content"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;
get_footer();
