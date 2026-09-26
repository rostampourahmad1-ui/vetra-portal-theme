<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( post_password_required() ) { return; }
?>
<section id="comments" class="vetra-comments" aria-labelledby="comments-title">
	<?php if ( have_comments() ) : ?><h2 id="comments-title"><?php printf( esc_html( _nx( '%1$s دیدگاه', '%1$s دیدگاه', get_comments_number(), 'comments title', 'vetra-portal' ) ), number_format_i18n( get_comments_number() ) ); ?></h2><ol class="comment-list"><?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 48 ) ); ?></ol><?php the_comments_pagination(); endif; ?>
	<?php if ( comments_open() ) : comment_form( array( 'title_reply' => esc_html__( 'دیدگاه خود را بنویسید', 'vetra-portal' ), 'label_submit' => esc_html__( 'ارسال دیدگاه', 'vetra-portal' ), 'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'فیلدهای ضروری با علامت مشخص شده‌اند.', 'vetra-portal' ) . '</p>' ) ); elseif ( get_comments_number() ) : ?><p><?php esc_html_e( 'دیدگاه‌ها بسته شده‌اند.', 'vetra-portal' ); ?></p><?php endif; ?>
</section>
