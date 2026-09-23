<?php
/**
 * Independent project portal data store.
 *
 * Projects are not posts, pages or a WordPress post type. They live in a
 * dedicated table so future plugins can consume the same project records.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_projects_table() {
	global $wpdb;
	return $wpdb->prefix . 'vetra_projects';
}

function vetra_project_schema_version() {
	return '1.0.0';
}

function vetra_project_install_schema() {
	global $wpdb;
	$table = vetra_projects_table();
	$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
	if ( $exists === $table && get_option( 'vetra_projects_schema_version' ) === vetra_project_schema_version() ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		project_name varchar(255) NOT NULL,
		client_name varchar(255) NOT NULL DEFAULT '',
		project_usage varchar(190) NOT NULL DEFAULT '',
		contract_type varchar(190) NOT NULL DEFAULT '',
		contract_number varchar(190) NOT NULL DEFAULT '',
		contractor varchar(255) NOT NULL DEFAULT '',
		contract_start varchar(80) NOT NULL DEFAULT '',
		contract_duration varchar(100) NOT NULL DEFAULT '',
		initial_amount varchar(120) NOT NULL DEFAULT '',
		project_supervisor varchar(255) NOT NULL DEFAULT '',
		project_manager varchar(255) NOT NULL DEFAULT '',
		site_supervisor varchar(255) NOT NULL DEFAULT '',
		project_address text NOT NULL,
		client_address text NOT NULL,
		urban_file_number varchar(190) NOT NULL DEFAULT '',
		registry_sub varchar(100) NOT NULL DEFAULT '',
		registry_main varchar(100) NOT NULL DEFAULT '',
		status varchar(20) NOT NULL DEFAULT 'pending',
		featured_image_id bigint(20) unsigned NOT NULL DEFAULT 0,
		category varchar(190) NOT NULL DEFAULT '',
		summary text NOT NULL,
		description longtext NOT NULL,
		created_by bigint(20) unsigned NOT NULL DEFAULT 0,
		created_at datetime NOT NULL,
		updated_at datetime NOT NULL,
		PRIMARY KEY  (id),
		KEY status (status),
		KEY created_by (created_by),
		KEY contract_number (contract_number)
	) {$charset};";
	dbDelta( $sql );
	update_option( 'vetra_projects_schema_version', vetra_project_schema_version(), false );
}
add_action( 'after_setup_theme', 'vetra_project_install_schema', 20 );

function vetra_project_flush_rewrites() {
	// Kept as a no-op migration hook for installations upgrading from the old CPT.
	vetra_project_install_schema();
}
add_action( 'after_switch_theme', 'vetra_project_flush_rewrites' );

function vetra_project_roles() {
	$editor_caps = array(
		'read' => true,
		'upload_files' => true,
		'vetra_submit_projects' => true,
		'vetra_edit_own_projects' => true,
	);
	$manager_caps = array_merge( $editor_caps, array(
		'vetra_manage_projects' => true,
		'vetra_edit_all_projects' => true,
		'vetra_approve_projects' => true,
		'vetra_delete_projects' => true,
	) );
	$editor = get_role( 'vetra_project_editor' );
	if ( ! $editor ) {
		$editor = add_role( 'vetra_project_editor', 'ویرایشگر پروژه وترا', $editor_caps );
	}
	if ( $editor ) {
		foreach ( array_keys( $editor_caps ) as $cap ) {
			$editor->add_cap( $cap );
		}
	}
	$manager = get_role( 'vetra_project_manager' );
	if ( ! $manager ) {
		$manager = add_role( 'vetra_project_manager', 'مدیر پروژه وترا', $manager_caps );
	}
	if ( $manager ) {
		foreach ( array_keys( $manager_caps ) as $cap ) {
			$manager->add_cap( $cap );
		}
	}
	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		foreach ( array_keys( $manager_caps ) as $cap ) {
			$administrator->add_cap( $cap );
		}
	}
}
add_action( 'after_setup_theme', 'vetra_project_roles' );

function vetra_project_fields() {
	return array(
		'client_name' => array( 'label' => 'نام کارفرما', 'type' => 'text' ),
		'project_usage' => array( 'label' => 'کاربری', 'type' => 'text' ),
		'contract_type' => array( 'label' => 'نوع قرارداد', 'type' => 'text' ),
		'contract_number' => array( 'label' => 'شماره قرارداد', 'type' => 'text' ),
		'contractor' => array( 'label' => 'پیمانکار', 'type' => 'text' ),
		'contract_start' => array( 'label' => 'تاریخ شروع قرارداد', 'type' => 'text' ),
		'contract_duration' => array( 'label' => 'مدت قرارداد', 'type' => 'text' ),
		'initial_amount' => array( 'label' => 'مبلغ اولیه پیمان', 'type' => 'text' ),
		'project_supervisor' => array( 'label' => 'ناظر پروژه', 'type' => 'text' ),
		'project_manager' => array( 'label' => 'مدیر پروژه', 'type' => 'text' ),
		'site_supervisor' => array( 'label' => 'سرپرست کارگاه', 'type' => 'text' ),
		'project_address' => array( 'label' => 'آدرس پروژه', 'type' => 'textarea' ),
		'client_address' => array( 'label' => 'آدرس کارفرما', 'type' => 'textarea' ),
		'urban_file_number' => array( 'label' => 'شماره پرونده شهرسازی', 'type' => 'text' ),
		'registry_sub' => array( 'label' => 'پلاک ثبتی فرعی', 'type' => 'text' ),
		'registry_main' => array( 'label' => 'پلاک ثبتی اصلی', 'type' => 'text' ),
	);
}

function vetra_project_extra_fields() {
	return array(
		'category' => array( 'label' => 'دسته پروژه', 'type' => 'text' ),
		'summary' => array( 'label' => 'خلاصه پروژه', 'type' => 'textarea' ),
		'description' => array( 'label' => 'توضیحات کامل', 'type' => 'textarea' ),
	);
}

function vetra_project_get( $id ) {
	global $wpdb;
	return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . vetra_projects_table() . ' WHERE id = %d', absint( $id ) ) );
}

function vetra_project_query( $args = array() ) {
	global $wpdb;
	$defaults = array( 'status' => 'approved', 'number' => 50, 'offset' => 0, 'created_by' => 0 );
	$args = wp_parse_args( $args, $defaults );
	$where = array( '1=1' );
	$values = array();
	if ( $args['status'] ) {
		$where[] = 'status = %s';
		$values[] = sanitize_key( $args['status'] );
	}
	if ( $args['created_by'] ) {
		$where[] = 'created_by = %d';
		$values[] = absint( $args['created_by'] );
	}
	$values[] = absint( $args['number'] );
	$values[] = absint( $args['offset'] );
	$sql = 'SELECT * FROM ' . vetra_projects_table() . ' WHERE ' . implode( ' AND ', $where ) . ' ORDER BY updated_at DESC LIMIT %d OFFSET %d';
	return $wpdb->get_results( $wpdb->prepare( $sql, $values ) );
}

function vetra_project_sanitize_data( $source ) {
	$data = array();
	foreach ( vetra_project_fields() as $key => $field ) {
		$value = wp_unslash( $source[ $key ] ?? '' );
		$data[ $key ] = 'textarea' === $field['type'] ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
	}
	foreach ( vetra_project_extra_fields() as $key => $field ) {
		$value = wp_unslash( $source[ $key ] ?? '' );
		$data[ $key ] = 'description' === $key ? wp_kses_post( $value ) : sanitize_textarea_field( $value );
	}
	$data['project_name'] = sanitize_text_field( wp_unslash( $source['project_name'] ?? '' ) );
	$data['status'] = in_array( $source['status'] ?? 'pending', array( 'pending', 'approved', 'rejected' ), true ) ? $source['status'] : 'pending';
	return $data;
}

function vetra_project_upload_image( $file ) {
	if ( empty( $file['name'] ) || ! current_user_can( 'upload_files' ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$uploaded = wp_handle_upload( $file, array( 'test_form' => false, 'mimes' => array( 'jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp' ) ) );
	if ( isset( $uploaded['error'] ) ) {
		return 0;
	}
	$attachment_id = wp_insert_attachment( array( 'post_mime_type' => $uploaded['type'], 'post_title' => sanitize_file_name( wp_basename( $uploaded['file'] ) ), 'post_status' => 'inherit' ), $uploaded['file'] );
	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}
	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $uploaded['file'] ) );
	return absint( $attachment_id );
}

function vetra_project_upsert( $data, $id = 0 ) {
	global $wpdb;
	$table = vetra_projects_table();
	$now = current_time( 'mysql' );
	$record = array_merge( array_fill_keys( array_keys( vetra_project_fields() ), '' ), array_fill_keys( array_keys( vetra_project_extra_fields() ), '' ), array( 'project_name' => '', 'status' => 'pending' ), $data );
	$record['updated_at'] = $now;
	if ( $id ) {
		$format = vetra_project_formats( $record );
		$result = $wpdb->update( $table, $record, array( 'id' => absint( $id ) ), $format, array( '%d' ) );
		return false === $result ? 0 : absint( $id );
	}
	$record['created_at'] = $now;
	$format = vetra_project_formats( $record );
	$result = $wpdb->insert( $table, $record, $format );
	return $result ? absint( $wpdb->insert_id ) : 0;
}

function vetra_project_formats( $record ) {
	$formats = array();
	foreach ( $record as $key => $value ) {
		$formats[] = in_array( $key, array( 'created_by', 'featured_image_id' ), true ) ? '%d' : '%s';
	}
	return $formats;
}

function vetra_project_can_edit( $project ) {
	return $project && ( current_user_can( 'vetra_edit_all_projects' ) || ( current_user_can( 'vetra_edit_own_projects' ) && get_current_user_id() === (int) $project->created_by ) );
}

function vetra_project_admin_menu() {
	add_menu_page( 'پروژه‌ها', 'پروژه‌ها', 'vetra_manage_projects', 'vetra-projects', 'vetra_projects_admin_page', 'dashicons-building', 25 );
	add_submenu_page( 'vetra-projects', 'افزودن پروژه', 'افزودن پروژه', 'vetra_manage_projects', 'vetra-projects-add', 'vetra_project_admin_form_page' );
}
add_action( 'admin_menu', 'vetra_project_admin_menu' );

function vetra_project_admin_actions() {
	if ( ! current_user_can( 'vetra_manage_projects' ) || empty( $_POST['vetra_project_admin_action'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_project_admin_nonce'] ?? '' ) ), 'vetra_project_admin' ) ) {
		return;
	}
	$action = sanitize_key( wp_unslash( $_POST['vetra_project_admin_action'] ) );
	if ( 'delete' === $action ) {
		global $wpdb;
		$wpdb->delete( vetra_projects_table(), array( 'id' => absint( $_POST['project_id'] ?? 0 ) ), array( '%d' ) );
		wp_safe_redirect( admin_url( 'admin.php?page=vetra-projects&deleted=1' ) );
		exit;
	}
	$data = vetra_project_sanitize_data( $_POST );
	$data['status'] = current_user_can( 'vetra_approve_projects' ) ? $data['status'] : 'pending';
	$data['created_by'] = absint( $_POST['created_by'] ?? get_current_user_id() );
	$id = absint( $_POST['project_id'] ?? 0 );
	if ( ! $id ) {
		$data['created_by'] = get_current_user_id();
	}
	$image_id = vetra_project_upload_image( $_FILES['featured_image'] ?? array() );
	if ( $image_id ) {
		$data['featured_image_id'] = $image_id;
	}
	$project_id = vetra_project_upsert( $data, $id );
	wp_safe_redirect( admin_url( 'admin.php?page=vetra-projects-add&project_id=' . $project_id . '&saved=1' ) );
	exit;
}
add_action( 'admin_init', 'vetra_project_admin_actions' );

function vetra_project_admin_page() {
	if ( ! current_user_can( 'vetra_manage_projects' ) ) {
		return;
	}
	$projects = vetra_project_query( array( 'status' => '', 'number' => 100 ) );
	?>
	<div class="wrap vetra-admin-wrap" dir="rtl">
		<h1>پروژه‌ها <a class="page-title-action" href="<?php echo esc_url( admin_url( 'admin.php?page=vetra-projects-add' ) ); ?>">افزودن پروژه</a></h1>
		<?php if ( isset( $_GET['deleted'] ) ) : ?><div class="notice notice-success"><p>پروژه حذف شد.</p></div><?php endif; ?>
		<div class="vetra-admin-card"><p>این بخش یک مخزن مستقل اطلاعات پروژه است و پروژه‌ها به‌عنوان پست یا برگه وردپرس ذخیره نمی‌شوند.</p></div>
		<table class="widefat striped"><thead><tr><th>نام پروژه</th><th>کارفرما</th><th>کاربری</th><th>شماره قرارداد</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
		<?php if ( $projects ) : foreach ( $projects as $project ) : ?>
			<tr><td><strong><?php echo esc_html( $project->project_name ); ?></strong></td><td><?php echo esc_html( $project->client_name ); ?></td><td><?php echo esc_html( $project->project_usage ); ?></td><td><?php echo esc_html( $project->contract_number ); ?></td><td><?php echo esc_html( vetra_project_status_label( $project->status ) ); ?></td><td><a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=vetra-projects-add&project_id=' . $project->id ) ); ?>">ویرایش</a></td></tr>
		<?php endforeach; else : ?><tr><td colspan="6">هنوز پروژه‌ای ثبت نشده است.</td></tr><?php endif; ?>
		</tbody></table>
	</div>
	<?php
}

function vetra_project_status_label( $status ) {
	return array( 'pending' => 'در انتظار بررسی', 'approved' => 'تأیید و قابل نمایش', 'rejected' => 'ردشده' )[ $status ] ?? 'در انتظار بررسی';
}

function vetra_project_render_fields( $project = null, $admin = false ) {
	$get = function( $key ) use ( $project ) { return $project ? ( $project->{$key} ?? '' ) : ''; };
	?>
	<div class="vetra-project-form__grid">
		<p><label>نام پروژه<input required type="text" name="project_name" value="<?php echo esc_attr( $get( 'project_name' ) ); ?>"></label></p>
		<?php foreach ( vetra_project_fields() as $key => $field ) : ?>
			<p><label><?php echo esc_html( $field['label'] ); ?><?php if ( 'textarea' === $field['type'] ) : ?><textarea name="<?php echo esc_attr( $key ); ?>" rows="3"><?php echo esc_textarea( $get( $key ) ); ?></textarea><?php else : ?><input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $get( $key ) ); ?>"><?php endif; ?></label></p>
		<?php endforeach; ?>
	</div>
	<div class="vetra-project-form__extras">
		<p><label>دسته پروژه<input type="text" name="category" value="<?php echo esc_attr( $get( 'category' ) ); ?>"></label></p>
		<p><label>تصویر شاخص پروژه<input type="file" name="featured_image" accept="image/*"></label></p>
		<p><label>خلاصه پروژه<textarea name="summary" rows="3"><?php echo esc_textarea( $get( 'summary' ) ); ?></textarea></label></p>
		<p><label>توضیحات کامل<textarea name="description" rows="6"><?php echo esc_textarea( $get( 'description' ) ); ?></textarea></label></p>
	</div>
	<?php if ( $admin ) : ?>
		<p><label>وضعیت پروژه<select name="status"><option value="pending" <?php selected( $get( 'status' ), 'pending' ); ?>>در انتظار بررسی</option><option value="approved" <?php selected( $get( 'status' ), 'approved' ); ?>>تأیید و قابل نمایش</option><option value="rejected" <?php selected( $get( 'status' ), 'rejected' ); ?>>ردشده</option></select></label></p>
	<?php endif; ?>
	<?php
}

function vetra_project_admin_form_page() {
	if ( ! current_user_can( 'vetra_manage_projects' ) ) {
		return;
	}
	$project = vetra_project_get( absint( $_GET['project_id'] ?? 0 ) );
	?>
	<div class="wrap vetra-admin-wrap" dir="rtl">
		<h1><?php echo $project ? 'ویرایش پروژه' : 'افزودن پروژه'; ?></h1>
		<?php if ( isset( $_GET['saved'] ) ) : ?><div class="notice notice-success"><p>اطلاعات پروژه ذخیره شد.</p></div><?php endif; ?>
		<div class="vetra-admin-card">
			<form class="vetra-project-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<input type="hidden" name="action" value="vetra_save_project">
				<input type="hidden" name="vetra_project_admin_action" value="save">
				<input type="hidden" name="project_id" value="<?php echo esc_attr( $project ? $project->id : 0 ); ?>">
				<?php wp_nonce_field( 'vetra_project_admin', 'vetra_project_admin_nonce' ); ?>
				<?php vetra_project_render_fields( $project, true ); ?>
				<button class="button button-primary">ذخیره اطلاعات پروژه</button>
			</form>
			<?php if ( $project ) : ?><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:12px"><input type="hidden" name="action" value="vetra_delete_project"><input type="hidden" name="vetra_project_admin_action" value="delete"><input type="hidden" name="project_id" value="<?php echo esc_attr( $project->id ); ?>"><?php wp_nonce_field( 'vetra_project_admin', 'vetra_project_admin_nonce' ); ?><button class="button" onclick="return confirm('حذف پروژه انجام شود؟');">حذف پروژه</button></form><?php endif; ?>
		</div>
	</div>
	<?php
}

function vetra_project_admin_post_handlers() {
	if ( 'vetra_save_project' === ( $_REQUEST['action'] ?? '' ) || 'vetra_delete_project' === ( $_REQUEST['action'] ?? '' ) ) {
		vetra_project_admin_actions();
	}
}
add_action( 'admin_post_vetra_save_project', 'vetra_project_admin_post_handlers' );
add_action( 'admin_post_vetra_delete_project', 'vetra_project_admin_post_handlers' );

function vetra_project_frontend_process() {
	if ( empty( $_POST['vetra_project_submit'] ) || ! is_user_logged_in() || ! current_user_can( 'vetra_submit_projects' ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_project_nonce'] ?? '' ) ), 'vetra_project_frontend' ) ) {
		return;
	}
	$data = vetra_project_sanitize_data( $_POST );
	if ( '' === $data['project_name'] ) {
		return;
	}
	$id = absint( $_POST['project_id'] ?? 0 );
	$project = $id ? vetra_project_get( $id ) : null;
	if ( $project && ! vetra_project_can_edit( $project ) ) {
		return;
	}
	$data['status'] = 'pending';
	$data['created_by'] = $project ? $project->created_by : get_current_user_id();
	$image_id = vetra_project_upload_image( $_FILES['featured_image'] ?? array() );
	if ( $image_id ) {
		$data['featured_image_id'] = $image_id;
	}
	$saved_id = vetra_project_upsert( $data, $id );
	if ( $saved_id ) {
		wp_safe_redirect( add_query_arg( 'vetra_project_saved', '1', wp_get_referer() ?: home_url( '/' ) ) );
		exit;
	}
}
add_action( 'template_redirect', 'vetra_project_frontend_process' );

function vetra_project_form_shortcode( $atts ) {
	if ( ! is_user_logged_in() || ! current_user_can( 'vetra_submit_projects' ) ) {
		return '<div class="vetra-content-card"><p>برای ثبت پروژه باید با حساب کاربری مجاز وارد شوید.</p></div>';
	}
	$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'vetra_project_form' );
	$project = vetra_project_get( absint( $atts['id'] ) );
	if ( $project && ! vetra_project_can_edit( $project ) ) {
		$project = null;
	}
	ob_start();
	?>
	<form class="vetra-project-form" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'vetra_project_frontend', 'vetra_project_nonce' ); ?><input type="hidden" name="vetra_project_submit" value="1"><input type="hidden" name="project_id" value="<?php echo esc_attr( $project ? $project->id : 0 ); ?>">
		<?php if ( isset( $_GET['vetra_project_saved'] ) ) : ?><p class="vetra-form-success">اطلاعات پروژه با موفقیت ثبت شد و برای بررسی ارسال شد.</p><?php endif; ?>
		<?php vetra_project_render_fields( $project ); ?>
		<button class="vetra-button vetra-button--primary" type="submit"><?php echo $project ? 'ذخیره ویرایش پروژه' : 'ثبت پروژه'; ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'vetra_project_form', 'vetra_project_form_shortcode' );

function vetra_project_list_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'number' => 12 ), $atts, 'vetra_project_list' );
	$projects = vetra_project_query( array( 'number' => absint( $atts['number'] ) ) );
	ob_start();
	if ( $projects ) {
		echo '<div class="vetra-project-grid vetra-project-grid--shortcode">';
		foreach ( $projects as $project ) {
			echo '<article class="vetra-project-card">';
			if ( $project->featured_image_id ) { echo wp_get_attachment_image( $project->featured_image_id, 'large' ); } else { echo '<div class="vetra-project-card__art vetra-project-card__art--1"><span>' . vetra_inline_icon( 'building' ) . '</span></div>'; }
			$detail = add_query_arg( 'vetra_project_id', $project->id, get_permalink() );
			echo '<div class="vetra-project-card__overlay"><span>' . esc_html( $project->project_usage ) . '</span><h3>' . esc_html( $project->project_name ) . '</h3><a href="' . esc_url( $detail ) . '" aria-label="مشاهده اطلاعات پروژه">' . vetra_inline_icon( 'arrow-up' ) . '</a></div></article>';
		}
		echo '</div>';
	} else {
		echo '<p class="vetra-content-card">هنوز پروژه‌ای برای نمایش تأیید نشده است.</p>';
	}
	return ob_get_clean();
}
add_shortcode( 'vetra_project_list', 'vetra_project_list_shortcode' );

function vetra_project_detail_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'id' => absint( $_GET['vetra_project_id'] ?? 0 ) ), $atts, 'vetra_project_detail' );
	$project = vetra_project_get( absint( $atts['id'] ) );
	if ( ! $project || ( 'approved' !== $project->status && ! vetra_project_can_edit( $project ) ) ) {
		return '<p class="vetra-content-card">اطلاعات پروژه در دسترس نیست.</p>';
	}
	ob_start();
	?><article class="vetra-project-single vetra-container"><header class="vetra-project-single__header"><span class="vetra-kicker"><i></i>اطلاعات پروژه</span><h1><?php echo esc_html( $project->project_name ); ?></h1><p><?php echo esc_html( $project->summary ); ?></p></header><?php if ( $project->featured_image_id ) : ?><div class="vetra-project-single__image"><?php echo wp_get_attachment_image( $project->featured_image_id, 'full' ); ?></div><?php endif; ?><div class="vetra-project-single__facts"><?php foreach ( array_merge( vetra_project_fields(), vetra_project_extra_fields() ) as $key => $field ) : $value = $project->{$key} ?? ''; if ( $value && 'description' !== $key && 'summary' !== $key ) : ?><div><span><?php echo esc_html( $field['label'] ); ?></span><strong><?php echo nl2br( esc_html( $value ) ); ?></strong></div><?php endif; endforeach; ?></div><div class="vetra-entry-content"><?php echo wpautop( wp_kses_post( $project->description ) ); ?></div></article><?php
	return ob_get_clean();
}
add_shortcode( 'vetra_project_detail', 'vetra_project_detail_shortcode' );

function vetra_roles_admin_menu() {
	add_users_page( 'نقش‌های کاربری وترا', 'نقش‌های وترا', 'manage_options', 'vetra-roles', 'vetra_roles_admin_page' );
}
add_action( 'admin_menu', 'vetra_roles_admin_menu' );

function vetra_roles_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$managed_caps = array( 'vetra_submit_projects' => 'ثبت پروژه با شورتکد', 'vetra_edit_own_projects' => 'ویرایش پروژه‌های خود', 'vetra_edit_all_projects' => 'ویرایش همه پروژه‌ها', 'vetra_approve_projects' => 'تأیید و نمایش پروژه', 'vetra_delete_projects' => 'حذف پروژه', 'upload_files' => 'آپلود تصویر' );
	$custom_roles = array_filter( wp_roles()->roles, function( $role, $slug ) { return 0 === strpos( $slug, 'vetra_' ); }, ARRAY_FILTER_USE_BOTH );
	?>
	<div class="wrap vetra-admin-wrap" dir="rtl"><h1>نقش‌های کاربری وترا</h1><p>سطح دسترسی ثبت پروژه از طریق شورتکد را برای کاربران مشخص کنید.</p><div class="vetra-admin-card"><h2>افزودن نقش</h2><form method="post"><?php wp_nonce_field( 'vetra_role_action', 'vetra_role_nonce' ); ?><input type="hidden" name="vetra_role_action" value="add"><p><label>نام نقش <input class="regular-text" required name="role_name" type="text"></label> <label>شناسه نقش <input class="regular-text" required name="role_slug" type="text" pattern="[a-z0-9_-]+"></label></p><?php foreach ( $managed_caps as $cap => $label ) : ?><label class="vetra-capability"><input type="checkbox" name="caps[]" value="<?php echo esc_attr( $cap ); ?>"> <?php echo esc_html( $label ); ?></label><?php endforeach; ?><p><button class="button button-primary">افزودن نقش</button></p></form></div><?php foreach ( $custom_roles as $slug => $role ) : ?><div class="vetra-admin-card"><h2><?php echo esc_html( $role['name'] ); ?> <code><?php echo esc_html( $slug ); ?></code></h2><form method="post"><?php wp_nonce_field( 'vetra_role_action', 'vetra_role_nonce' ); ?><input type="hidden" name="vetra_role_action" value="update"><input type="hidden" name="role_slug" value="<?php echo esc_attr( $slug ); ?>"><p><label>نام نقش <input class="regular-text" required name="role_name" type="text" value="<?php echo esc_attr( $role['name'] ); ?>"></label></p><?php foreach ( $managed_caps as $cap => $label ) : ?><label class="vetra-capability"><input type="checkbox" name="caps[]" value="<?php echo esc_attr( $cap ); ?>" <?php checked( ! empty( $role['capabilities'][ $cap ] ) ); ?>> <?php echo esc_html( $label ); ?></label><?php endforeach; ?><p><button class="button button-primary">ذخیره نقش</button></p></form></div><?php endforeach; ?><p><code>[vetra_project_form]</code> ثبت/ویرایش پروژه و <code>[vetra_project_list]</code> فهرست پروژه‌ها و <code>[vetra_project_detail id="123"]</code> جزئیات پروژه.</p></div>
	<?php
}

function vetra_roles_admin_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) || empty( $_POST['vetra_role_action'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_role_nonce'] ?? '' ) ), 'vetra_role_action' ) ) { return; }
	$action = sanitize_key( wp_unslash( $_POST['vetra_role_action'] ) );
	$slug = sanitize_key( wp_unslash( $_POST['role_slug'] ?? '' ) );
	$caps = array_fill_keys( array_map( 'sanitize_key', (array) ( $_POST['caps'] ?? array() ) ), true );
	$caps['read'] = true;
	if ( 'add' === $action && $slug && ! get_role( $slug ) ) {
		add_role( $slug, sanitize_text_field( wp_unslash( $_POST['role_name'] ?? $slug ) ), $caps );
	} elseif ( 'update' === $action && $slug && get_role( $slug ) && 0 === strpos( $slug, 'vetra_' ) ) {
		$role = get_role( $slug );
		global $wp_roles;
		$wp_roles->roles[ $slug ]['name'] = sanitize_text_field( wp_unslash( $_POST['role_name'] ?? $slug ) );
		update_option( $wp_roles->role_key, $wp_roles->roles );
		foreach ( array( 'vetra_submit_projects', 'vetra_edit_own_projects', 'vetra_edit_all_projects', 'vetra_approve_projects', 'vetra_delete_projects', 'upload_files' ) as $cap ) { if ( isset( $caps[ $cap ] ) ) { $role->add_cap( $cap ); } else { $role->remove_cap( $cap ); } }
	}
	wp_safe_redirect( admin_url( 'users.php?page=vetra-roles' ) );
	exit;
}
add_action( 'admin_init', 'vetra_roles_admin_actions' );

function vetra_project_admin_assets( $hook ) {
	if ( 'users_page_vetra-roles' === $hook || 'toplevel_page_vetra-projects' === $hook || 'vetra-projects_page_vetra-projects-add' === $hook ) {
		wp_enqueue_style( 'vetra-project-admin', VETRA_PORTAL_URI . '/assets/css/project-admin.css', array(), VETRA_PORTAL_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'vetra_project_admin_assets' );
