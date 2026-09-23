<?php
$post_id       = get_the_ID();
$terms         = vetra_form_terms( $post_id );
$category_slugs = wp_list_pluck( $terms, 'slug' );
$category_names = wp_list_pluck( $terms, 'name' );
$title         = get_the_title();
$description   = vetra_field( 'form_description', $post_id, get_the_excerpt() );
$badge         = vetra_field( 'form_badge', $post_id, 'عملیاتی' );
$code          = vetra_field( 'form_code', $post_id, 'VETRA-FORM' );
$time          = vetra_field( 'form_completion_time', $post_id, 'ثبت آنلاین' );
$output        = vetra_field( 'form_output_format', $post_id, 'PDF' );
$allowed       = vetra_user_can_view_form( $post_id );
$search_text   = implode( ' ', array_merge( array( $title, $description, $code, $badge ), $category_names ) );
?>
<article class="vetra-form-card" data-categories="<?php echo esc_attr( implode( ' ', $category_slugs ) ); ?>" data-search="<?php echo esc_attr( $search_text ); ?>">
	<div class="vetra-form-card__top"><span class="vetra-form-badge"><?php echo esc_html( $badge ); ?></span><span class="vetra-form-code"><?php echo esc_html( $code ); ?></span></div>
	<div class="vetra-form-icon"><?php echo vetra_render_form_icon( vetra_field( 'form_icon', $post_id ), 'file' ); ?></div>
	<h2><?php echo esc_html( $title ); ?></h2>
	<p><?php echo esc_html( $description ); ?></p>
	<?php if ( vetra_option( 'show_form_metadata' ) ) : ?><div class="vetra-form-card__meta"><span><i><?php echo vetra_inline_icon( 'calendar' ); ?></i><?php echo esc_html( $time ); ?></span><span><i><?php echo vetra_inline_icon( 'file' ); ?></i><?php echo esc_html( $output ); ?></span></div><?php endif; ?>
	<?php if ( $allowed ) : ?>
		<a class="vetra-primary-button" href="<?php echo esc_url( vetra_form_url( $post_id ) ); ?>"><?php echo esc_html( vetra_option( 'form_button_text' ) ); ?> <span>←</span></a>
	<?php else : ?>
		<a class="vetra-secondary-button" href="<?php echo esc_url( wp_login_url( get_permalink( $post_id ) ) ); ?>">ورود برای دسترسی <span>←</span></a>
	<?php endif; ?>
</article>
