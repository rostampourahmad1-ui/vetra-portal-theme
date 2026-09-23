<?php
get_header();
while ( have_posts() ) :
	the_post();
	$post_id       = get_the_ID();
	$code          = vetra_field( 'form_code', $post_id, 'VETRA-FORM' );
	$description   = vetra_field( 'form_description', $post_id, get_the_excerpt() );
	$badge         = vetra_field( 'form_badge', $post_id, 'عملیاتی' );
	$time          = vetra_field( 'form_completion_time', $post_id, 'ثبت آنلاین' );
	$output        = vetra_field( 'form_output_format', $post_id, 'PDF' );
	$terms         = vetra_form_terms( $post_id );
	?>
	<article class="vetra-form-single">
		<a class="vetra-back-link" href="<?php echo esc_url( get_post_type_archive_link( 'vetra_form' ) ); ?>">→ بازگشت به مرکز فرم‌ها</a>
		<div class="vetra-form-single__hero">
			<div class="vetra-form-icon is-large"><?php echo vetra_render_form_icon( vetra_field( 'form_icon', $post_id ), 'file' ); ?></div>
			<div><span class="vetra-form-badge"><?php echo esc_html( $badge ); ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html( $description ); ?></p></div>
		</div>
		<div class="vetra-form-single__meta"><span><small>کد فرم</small><strong><?php echo esc_html( $code ); ?></strong></span><span><small>زمان تکمیل</small><strong><?php echo esc_html( $time ); ?></strong></span><span><small>فرمت خروجی</small><strong><?php echo esc_html( $output ); ?></strong></span><span><small>دسته‌بندی</small><strong><?php echo esc_html( $terms ? $terms[0]->name : 'عمومی' ); ?></strong></span></div>
		<div class="vetra-form-single__body"><div class="vetra-entry-content"><?php the_content(); ?></div><a class="vetra-primary-button" href="<?php echo esc_url( vetra_form_url( $post_id ) ); ?>">تکمیل و ارسال فرم <span>←</span></a></div>
	</article>
	<?php
endwhile;
get_footer();
