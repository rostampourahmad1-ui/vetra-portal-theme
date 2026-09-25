<?php
/** Server-side access rules, consent, maintenance mode and starter content. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function vetra_user_has_any_role( $roles ) {
	if ( current_user_can( 'manage_options' ) ) { return true; }
	$user = wp_get_current_user();
	return (bool) array_intersect( (array) $roles, (array) $user->roles );
}

function vetra_page_access_meta_box() {
	add_meta_box( 'vetra_page_roles', 'دسترسی بر اساس نقش', 'vetra_page_roles_meta_box', 'page', 'side' );
}
add_action( 'add_meta_boxes_page', 'vetra_page_access_meta_box' );
function vetra_page_roles_meta_box( $post ) {
	wp_nonce_field( 'vetra_page_roles', 'vetra_page_roles_nonce' );
	$selected = (array) get_post_meta( $post->ID, '_vetra_allowed_roles', true );
	$roles = wp_roles()->roles;
	echo '<p>در حالت پیش‌فرض برگه عمومی است.</p>';
	foreach ( $roles as $slug => $role ) {
		printf( '<label style="display:block"><input type="checkbox" name="vetra_allowed_roles[]" value="%s" %s> %s</label>', esc_attr( $slug ), checked( in_array( $slug, $selected, true ), true, false ), esc_html( translate_user_role( $role['name'] ) ) );
	}
}
function vetra_save_page_roles( $post_id ) {
	if ( ! isset( $_POST['vetra_page_roles_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_page_roles_nonce'] ) ), 'vetra_page_roles' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_page', $post_id ) ) { return; }
	$roles = array_map( 'sanitize_key', (array) ( $_POST['vetra_allowed_roles'] ?? array() ) );
	$roles = array_values( array_intersect( $roles, array_keys( wp_roles()->roles ) ) );
	if ( $roles ) { update_post_meta( $post_id, '_vetra_allowed_roles', $roles ); } else { delete_post_meta( $post_id, '_vetra_allowed_roles' ); }
}
add_action( 'save_post_page', 'vetra_save_page_roles' );

function vetra_enforce_page_access() {
	if ( ! is_page() ) { return; }
	$roles = (array) get_post_meta( get_queried_object_id(), '_vetra_allowed_roles', true );
	if ( $roles && ! vetra_user_has_any_role( $roles ) ) {
		status_header( is_user_logged_in() ? 403 : 401 );
		nocache_headers();
		wp_die( is_user_logged_in() ? 'نقش کاربری شما اجازهٔ مشاهدهٔ این برگه را ندارد.' : 'برای مشاهدهٔ این برگه وارد حساب کاربری مجاز شوید.', 'دسترسی محدود', array( 'response' => is_user_logged_in() ? 403 : 401 ) );
	}
}
add_action( 'template_redirect', 'vetra_enforce_page_access', 0 );
function vetra_protect_rest_pages( $response, $post, $request ) {
	$roles = (array) get_post_meta( $post->ID, '_vetra_allowed_roles', true );
	if ( $roles && ! vetra_user_has_any_role( $roles ) ) { return new WP_Error( 'vetra_restricted_page', 'دسترسی به این برگه مجاز نیست.', array( 'status' => is_user_logged_in() ? 403 : 401 ) ); }
	return $response;
}
add_filter( 'rest_prepare_page', 'vetra_protect_rest_pages', 10, 3 );
function vetra_filter_menu_by_role( $items ) {
	foreach ( $items as $index => $item ) {
		$page_id = 'page' === $item->object ? absint( $item->object_id ) : 0;
		$roles = $page_id ? (array) get_post_meta( $page_id, '_vetra_allowed_roles', true ) : array();
		if ( $roles && ! vetra_user_has_any_role( $roles ) ) { unset( $items[ $index ] ); }
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'vetra_filter_menu_by_role' );
function vetra_restricted_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'roles' => '' ), $atts, 'vetra_restrict' );
	$roles = array_filter( array_map( 'sanitize_key', explode( ',', $atts['roles'] ) ) );
	return $roles && vetra_user_has_any_role( $roles ) ? do_shortcode( $content ) : '';
}
add_shortcode( 'vetra_restrict', 'vetra_restricted_shortcode' );

function vetra_theme_admin_page() { add_theme_page( 'تنظیمات و ابزارهای وترا', 'تنظیمات وترا', 'manage_options', 'vetra-site-tools', 'vetra_theme_admin_page_render' ); }
add_action( 'admin_menu', 'vetra_theme_admin_page' );
function vetra_theme_options() {
	return wp_parse_args( get_option( 'vetra_site_options', array() ), array( 'cookie_enabled' => 0, 'cookie_text' => 'این وب‌سایت برای بهبود تجربهٔ شما از کوکی استفاده می‌کند.', 'cookie_position' => 'bottom', 'policy_page' => 0, 'site_mode' => 'normal', 'maintenance_text' => 'وب‌سایت در حال تعمیر است. لطفاً بعداً مراجعه کنید.', 'update_text' => 'وب‌سایت در حال به‌روزرسانی است. لطفاً چند دقیقهٔ دیگر مراجعه کنید.' ) );
}
function vetra_theme_admin_page_render() {
	if ( isset( $_POST['vetra_site_options_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_site_options_nonce'] ) ), 'vetra_site_options' ) ) {
		$input = array( 'cookie_enabled' => empty( $_POST['cookie_enabled'] ) ? 0 : 1, 'cookie_text' => sanitize_textarea_field( wp_unslash( $_POST['cookie_text'] ?? '' ) ), 'cookie_position' => in_array( $_POST['cookie_position'] ?? '', array( 'bottom', 'top' ), true ) ? $_POST['cookie_position'] : 'bottom', 'policy_page' => absint( $_POST['policy_page'] ?? 0 ), 'site_mode' => in_array( $_POST['site_mode'] ?? '', array( 'normal', 'maintenance', 'updating' ), true ) ? $_POST['site_mode'] : 'normal', 'maintenance_text' => sanitize_textarea_field( wp_unslash( $_POST['maintenance_text'] ?? '' ) ), 'update_text' => sanitize_textarea_field( wp_unslash( $_POST['update_text'] ?? '' ) ) );
		update_option( 'vetra_site_options', $input, false );
		echo '<div class="notice notice-success"><p>تنظیمات ذخیره شد.</p></div>';
	}
	$o = vetra_theme_options();
	?><div class="wrap" dir="rtl"><h1>تنظیمات و ابزارهای وترا</h1><form method="post"><?php wp_nonce_field( 'vetra_site_options', 'vetra_site_options_nonce' ); ?><h2>پیام کوکی</h2><p><label><input type="checkbox" name="cookie_enabled" value="1" <?php checked( $o['cookie_enabled'] ); ?>> نمایش پیام کوکی</label></p><p><textarea class="large-text" name="cookie_text" rows="2"><?php echo esc_textarea( $o['cookie_text'] ); ?></textarea></p><p>جایگاه <select name="cookie_position"><option value="bottom" <?php selected( $o['cookie_position'], 'bottom' ); ?>>پایین</option><option value="top" <?php selected( $o['cookie_position'], 'top' ); ?>>بالا</option></select> برگه قوانین <select name="policy_page"><option value="0">انتخاب کنید</option><?php foreach ( get_pages() as $page ) : ?><option value="<?php echo absint( $page->ID ); ?>" <?php selected( $o['policy_page'], $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option><?php endforeach; ?></select></p><h2>وضعیت وب‌سایت</h2><select name="site_mode"><option value="normal" <?php selected( $o['site_mode'], 'normal' ); ?>>عادی</option><option value="maintenance" <?php selected( $o['site_mode'], 'maintenance' ); ?>>در حال تعمیر</option><option value="updating" <?php selected( $o['site_mode'], 'updating' ); ?>>در حال به‌روزرسانی</option></select><p>پیام تعمیر<textarea class="large-text" name="maintenance_text"><?php echo esc_textarea( $o['maintenance_text'] ); ?></textarea></p><p>پیام به‌روزرسانی<textarea class="large-text" name="update_text"><?php echo esc_textarea( $o['update_text'] ); ?></textarea></p><?php submit_button( 'ذخیره تنظیمات' ); ?></form><hr><h2>نمونه‌برگه‌ها</h2><p>برگه‌های نمونه با شناسهٔ ثابت پوسته ساخته می‌شوند و اجرای دوباره نسخهٔ تکراری نمی‌سازد.</p><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="vetra_sample_pages"><input type="hidden" name="operation" value="install"><?php wp_nonce_field( 'vetra_sample_pages' ); ?><?php submit_button( 'ایجاد یا بازسازی نمونه‌برگه‌ها', 'secondary', 'submit', false ); ?></form><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('فقط برگه‌های نمونهٔ ساخته‌شده توسط پوسته حذف شوند؟');"><input type="hidden" name="action" value="vetra_sample_pages"><input type="hidden" name="operation" value="delete"><?php wp_nonce_field( 'vetra_sample_pages' ); ?><?php submit_button( 'حذف نمونه‌برگه‌های پوسته', 'delete', 'submit', false ); ?></form></div><?php
}

function vetra_handle_sample_pages() {
	if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'اجازهٔ این عملیات را ندارید.', '', array( 'response' => 403 ) ); }
	check_admin_referer( 'vetra_sample_pages' );
	$operation = sanitize_key( wp_unslash( $_POST['operation'] ?? '' ) );
	$samples = array( 'about' => array( 'درباره وترا', 'دربارهٔ وترا', 'گروه ساختمانی و مهندسی وترا با تکیه بر معماری، مهندسی و اجرای دقیق، فضاهای ماندگار می‌سازد.' ), 'services' => array( 'خدمات وترا', 'خدمات یکپارچهٔ ساخت', 'طراحی و معماری، مدیریت مهندسی و اجرای پروژه را در یک مسیر یکپارچه بررسی کنید.' ), 'contact' => array( 'تماس با وترا', 'برای پروژهٔ بعدی گفتگو کنیم', 'با تیم وترا دربارهٔ نیازها و زمان‌بندی پروژهٔ خود گفتگو کنید.' ) );
	foreach ( $samples as $key => $sample ) {
		$existing = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'meta_key' => '_vetra_sample_key', 'meta_value' => $key, 'numberposts' => 1 ) );
		if ( 'delete' === $operation ) { if ( $existing ) { wp_delete_post( $existing[0]->ID, true ); } continue; }
		$post = array( 'post_title' => $sample[0], 'post_name' => 'vetra-' . $key, 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">' . esc_html( $sample[1] ) . '</h1><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html( $sample[2] ) . '</p><!-- /wp:paragraph -->' );
		if ( $existing ) { $post['ID'] = $existing[0]->ID; $id = wp_update_post( $post, true ); } else { $id = wp_insert_post( $post, true ); }
		if ( ! is_wp_error( $id ) ) { update_post_meta( $id, '_vetra_sample_key', $key ); }
	}
	wp_safe_redirect( admin_url( 'themes.php?page=vetra-site-tools&samples=' . ( 'delete' === $operation ? 'deleted' : 'ready' ) ) ); exit;
}
add_action( 'admin_post_vetra_sample_pages', 'vetra_handle_sample_pages' );

function vetra_maintenance_response() {
	$o = vetra_theme_options();
	if ( 'normal' === $o['site_mode'] || current_user_can( 'manage_options' ) || is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) { return; }
	$message = 'updating' === $o['site_mode'] ? $o['update_text'] : $o['maintenance_text'];
	status_header( 503 ); header( 'Retry-After: 3600' ); nocache_headers();
	wp_die( '<main dir="rtl" style="font-family:Tahoma,sans-serif;max-width:720px;margin:12vh auto;padding:32px;text-align:center"><h1>' . esc_html( 'updating' === $o['site_mode'] ? 'سایت در حال به‌روزرسانی' : 'سایت در حال تعمیر' ) . '</h1><p>' . esc_html( $message ) . '</p></main>', 'وترا', array( 'response' => 503 ) );
}
add_action( 'template_redirect', 'vetra_maintenance_response', 0 );

function vetra_cookie_banner() {
	$o = vetra_theme_options(); if ( empty( $o['cookie_enabled'] ) ) { return; }
	$position = 'top' === $o['cookie_position'] ? 'top:16px' : 'bottom:16px';
	echo '<aside class="vetra-cookie-banner" role="dialog" aria-label="پیام کوکی" style="' . esc_attr( $position ) . '"><span>' . esc_html( $o['cookie_text'] ) . '</span>';
	if ( $o['policy_page'] && get_post_status( $o['policy_page'] ) === 'publish' ) { echo ' <a href="' . esc_url( get_permalink( $o['policy_page'] ) ) . '">قوانین و مقررات</a>'; }
	echo ' <button type="button" class="js-vetra-cookie-dismiss">متوجه شدم</button></aside>';
}
add_action( 'wp_footer', 'vetra_cookie_banner', 90 );
function vetra_pwa_install_banner() {
	if ( ! vetra_option( 'pwa_enabled' ) || ! vetra_option( 'pwa_install_prompt', true ) ) { return; }
	echo '<aside class="vetra-install-banner" hidden><span>' . esc_html( vetra_option( 'pwa_install_text', 'برای دسترسی سریع‌تر، وب‌اپ وترا را نصب کنید.' ) ) . '</span><button class="js-vetra-install" type="button">نصب</button><button class="js-vetra-install-dismiss" type="button" aria-label="بستن">×</button></aside>';
}
add_action( 'wp_footer', 'vetra_pwa_install_banner', 95 );

function vetra_register_company_patterns() {
	register_block_pattern_category( 'vetra-company', array( 'label' => 'وترا: صفحات شرکتی' ) );
	$patterns = array( 'hero' => array( 'معرفی شرکت', '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"64px","bottom":"64px"}}},"layout":{"type":"constrained"}} --><div class="wp-block-group alignfull" style="padding-top:64px;padding-bottom:64px"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">ساختن آینده‌ای ماندگار</h1><!-- /wp:heading --><!-- wp:paragraph --><p>معماری، مهندسی و اجرای یکپارچه برای پروژه‌های ارزش‌آفرین.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">شروع گفتگو</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->' ), 'services' => array( 'معرفی خدمات', '<!-- wp:heading --><h2 class="wp-block-heading">خدمات ما</h2><!-- /wp:heading --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">طراحی و معماری</h3><!-- /wp:heading --><!-- wp:paragraph --><p>راهکارهای انسانی، دقیق و متناسب با پروژه.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">مدیریت مهندسی</h3><!-- /wp:heading --><!-- wp:paragraph --><p>مدیریت زمان، هزینه و کیفیت در یک مسیر روشن.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">ساخت و توسعه</h3><!-- /wp:heading --><!-- wp:paragraph --><p>اجرای استانداردمحور با توجه به جزئیات.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->' ), 'contact' => array( 'دعوت به تماس', '<!-- wp:group {"style":{"spacing":{"padding":{"top":"32px","bottom":"32px"}}},"layout":{"type":"constrained"}} --><div class="wp-block-group" style="padding-top:32px;padding-bottom:32px"><!-- wp:heading --><h2 class="wp-block-heading">دربارهٔ پروژه‌تان صحبت کنیم؟</h2><!-- /wp:heading --><!-- wp:paragraph --><p>نیازها و چشم‌انداز پروژه را با تیم وترا در میان بگذارید.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>تلفن: ۰۲۱-۲۲۷۷۰۰۰۰</p><!-- /wp:paragraph --></div><!-- /wp:group -->' ) );
	foreach ( $patterns as $slug => $pattern ) { register_block_pattern( 'vetra/' . $slug, array( 'title' => $pattern[0], 'categories' => array( 'vetra-company' ), 'content' => $pattern[1] ) ); }
}
add_action( 'init', 'vetra_register_company_patterns' );
