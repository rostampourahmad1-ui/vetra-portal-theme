<?php
/**
 * Shared project content type, roles and front-end project tools.
 *
 * @package VetraPortal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vetra_register_project_type() {
	register_post_type(
		'vetra_project',
		array(
			'labels' => array(
				'name' => 'پروژه‌ها',
				'singular_name' => 'پروژه',
				'menu_name' => 'پروژه‌ها',
				'add_new' => 'افزودن پروژه',
				'add_new_item' => 'افزودن پروژه جدید',
				'edit_item' => 'ویرایش پروژه',
				'new_item' => 'پروژه جدید',
				'view_item' => 'مشاهده پروژه',
				'search_items' => 'جستجوی پروژه‌ها',
				'not_found' => 'پروژه‌ای پیدا نشد',
			),
			'public' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-building',
			'rewrite' => array( 'slug' => 'projects' ),
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'has_archive' => true,
			'capability_type' => array( 'project', 'projects' ),
			'map_meta_cap' => true,
		)
	);

	register_taxonomy(
		'vetra_project_category',
		'vetra_project',
		array(
			'labels' => array( 'name' => 'دسته‌های پروژه', 'singular_name' => 'دسته پروژه' ),
			'public' => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'project-category' ),
		)
	);
}
add_action( 'init', 'vetra_register_project_type' );

function vetra_project_flush_rewrites() {
	vetra_register_project_type();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'vetra_project_flush_rewrites' );

function vetra_project_roles() {
	$capabilities = array(
		'read' => true,
		'upload_files' => true,
		'vetra_submit_projects' => true,
		'read_project' => true,
		'edit_project' => true,
		'delete_project' => true,
		'edit_projects' => true,
		'edit_published_projects' => true,
		'delete_projects' => true,
		'delete_published_projects' => true,
	);
	$editor = get_role( 'vetra_project_editor' );
	if ( ! $editor ) {
		$editor = add_role( 'vetra_project_editor', 'ویرایشگر پروژه وترا', $capabilities );
	}
	if ( $editor ) {
		foreach ( array_keys( $capabilities ) as $capability ) {
			$editor->add_cap( $capability );
		}
	}
	$manager = get_role( 'vetra_project_manager' );
	if ( ! $manager ) {
		$manager = add_role( 'vetra_project_manager', 'مدیر پروژه وترا', array_merge( $capabilities, array(
			'edit_others_projects' => true,
			'publish_projects' => true,
			'delete_others_projects' => true,
			'read_private_projects' => true,
		) ) );
	}
	if ( $manager ) {
		foreach ( array_merge( array_keys( $capabilities ), array( 'edit_others_projects', 'publish_projects', 'delete_others_projects', 'read_private_projects' ) ) as $capability ) {
			$manager->add_cap( $capability );
		}
	}
	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		foreach ( array_keys( $capabilities ) as $capability ) {
			$administrator->add_cap( $capability );
		}
		$administrator->add_cap( 'edit_others_projects' );
		$administrator->add_cap( 'publish_projects' );
		$administrator->add_cap( 'delete_others_projects' );
		$administrator->add_cap( 'read_private_projects' );
	}
}
add_action( 'after_setup_theme', 'vetra_project_roles' );

function vetra_project_meta_boxes() {
	add_meta_box( 'vetra_project_details', 'اطلاعات پروژه', 'vetra_project_details_box', 'vetra_project', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'vetra_project_meta_boxes' );

function vetra_project_fields() {
	return array(
		'project_code' => array( 'label' => 'کد پروژه', 'type' => 'text' ),
		'project_client' => array( 'label' => 'کارفرما', 'type' => 'text' ),
		'project_location' => array( 'label' => 'موقعیت', 'type' => 'text' ),
		'project_type' => array( 'label' => 'نوع پروژه', 'type' => 'text' ),
		'project_year' => array( 'label' => 'سال اجرا', 'type' => 'text' ),
		'project_status' => array( 'label' => 'وضعیت', 'type' => 'select', 'options' => array( 'planning' => 'در مرحله طراحی', 'active' => 'در حال اجرا', 'completed' => 'تکمیل‌شده' ) ),
		'project_area' => array( 'label' => 'زیربنا / مساحت', 'type' => 'text' ),
		'project_budget' => array( 'label' => 'بودجه پروژه', 'type' => 'text' ),
		'project_architect' => array( 'label' => 'معمار / طراح', 'type' => 'text' ),
		'project_manager' => array( 'label' => 'مدیر پروژه', 'type' => 'text' ),
		'project_completion' => array( 'label' => 'تاریخ تحویل', 'type' => 'text' ),
		'project_website' => array( 'label' => 'لینک پروژه', 'type' => 'url' ),
	);
}

function vetra_project_details_box( $post ) {
	wp_nonce_field( 'vetra_project_details', 'vetra_project_details_nonce' );
	$fields = vetra_project_fields();
	?>
	<div class="vetra-project-admin-grid">
		<?php foreach ( $fields as $key => $field ) : $value = get_post_meta( $post->ID, '_' . $key, true ); ?>
			<p>
				<label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $field['label'] ); ?></strong></label>
				<?php if ( 'select' === $field['type'] ) : ?>
				<select id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>">
					<?php foreach ( $field['options'] as $option => $label ) : ?><option value="<?php echo esc_attr( $option ); ?>" <?php selected( $value, $option ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?>
				</select>
				<?php else : ?>
				<input class="widefat" type="<?php echo esc_attr( $field['type'] ); ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
				<?php endif; ?>
			</p>
		<?php endforeach; ?>
	</div>
	<p class="description">تصویر شاخص، عنوان، متن کامل و دسته پروژه را از بخش‌های استاندارد وردپرس تکمیل کنید.</p>
	<?php
}

function vetra_save_project_details( $post_id ) {
	if ( ! isset( $_POST['vetra_project_details_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_project_details_nonce'] ) ), 'vetra_project_details' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( 'vetra_project' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( vetra_project_fields() as $key => $field ) {
		$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$value = 'url' === $field['type'] ? esc_url_raw( $value ) : sanitize_text_field( $value );
		if ( '' === $value ) {
			delete_post_meta( $post_id, '_' . $key );
		} else {
			update_post_meta( $post_id, '_' . $key, $value );
		}
	}
}
add_action( 'save_post_vetra_project', 'vetra_save_project_details' );

function vetra_project_frontend_process() {
	if ( empty( $_POST['vetra_project_submit'] ) || ! is_user_logged_in() ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_project_nonce'] ?? '' ) ), 'vetra_project_frontend' ) || ! current_user_can( 'vetra_submit_projects' ) ) {
		return;
	}
	$title = sanitize_text_field( wp_unslash( $_POST['project_title'] ?? '' ) );
	if ( '' === $title ) {
		return;
	}
	$post_id = absint( $_POST['project_id'] ?? 0 );
	$data = array(
		'post_title' => $title,
		'post_content' => wp_kses_post( wp_unslash( $_POST['project_content'] ?? '' ) ),
		'post_excerpt' => sanitize_textarea_field( wp_unslash( $_POST['project_excerpt'] ?? '' ) ),
		'post_type' => 'vetra_project',
		'post_author' => get_current_user_id(),
		'post_status' => current_user_can( 'publish_projects' ) ? 'publish' : 'pending',
	);
	if ( $post_id && 'vetra_project' === get_post_type( $post_id ) && ( (int) get_post_field( 'post_author', $post_id ) === get_current_user_id() || current_user_can( 'edit_others_projects' ) ) ) {
		$data['ID'] = $post_id;
		unset( $data['post_author'] );
		$post_id = wp_update_post( $data, true );
	} else {
		$post_id = wp_insert_post( $data, true );
	}
	if ( is_wp_error( $post_id ) ) {
		return;
	}
	foreach ( array( 'project_client', 'project_location', 'project_type', 'project_year', 'project_status', 'project_area', 'project_budget', 'project_architect', 'project_manager', 'project_completion', 'project_website' ) as $key ) {
		$value = 'project_website' === $key ? esc_url_raw( wp_unslash( $_POST[ $key ] ?? '' ) ) : sanitize_text_field( wp_unslash( $_POST[ $key ] ?? '' ) );
		update_post_meta( $post_id, '_' . $key, $value );
	}
	if ( ! empty( $_FILES['project_image']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attachment_id = media_handle_upload( 'project_image', $post_id );
		if ( ! is_wp_error( $attachment_id ) ) {
			set_post_thumbnail( $post_id, $attachment_id );
		}
	}
	if ( isset( $_POST['project_category'] ) ) {
		$category_id = absint( $_POST['project_category'] );
		wp_set_object_terms( $post_id, $category_id ? array( $category_id ) : array(), 'vetra_project_category' );
	}
	wp_safe_redirect( add_query_arg( 'vetra_project_saved', '1', wp_get_referer() ?: home_url( '/' ) ) );
	exit;
}
add_action( 'template_redirect', 'vetra_project_frontend_process' );

function vetra_project_form_shortcode( $atts ) {
	if ( ! is_user_logged_in() || ! current_user_can( 'vetra_submit_projects' ) ) {
		return '<div class="vetra-content-card"><p>برای ثبت پروژه باید با حساب کاربری مجاز وارد شوید.</p></div>';
	}
	$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'vetra_project_form' );
	$post = absint( $atts['id'] ) ? get_post( absint( $atts['id'] ) ) : null;
	if ( $post && ( 'vetra_project' !== $post->post_type || (int) $post->post_author !== get_current_user_id() ) && ! current_user_can( 'edit_others_projects' ) ) {
		$post = null;
	}
	$value = function( $key ) use ( $post ) { return $post ? get_post_meta( $post->ID, '_' . $key, true ) : ''; };
	ob_start();
	?>
	<form class="vetra-project-form" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'vetra_project_frontend', 'vetra_project_nonce' ); ?>
		<input type="hidden" name="vetra_project_submit" value="1">
		<input type="hidden" name="project_id" value="<?php echo esc_attr( $post ? $post->ID : 0 ); ?>">
		<?php if ( isset( $_GET['vetra_project_saved'] ) ) : ?><p class="vetra-form-success">پروژه با موفقیت ثبت شد.</p><?php endif; ?>
		<div class="vetra-project-form__grid">
			<p><label>عنوان پروژه<input required type="text" name="project_title" value="<?php echo esc_attr( $post ? $post->post_title : '' ); ?>"></label></p>
			<p><label>دسته پروژه<select name="project_category"><option value="0">بدون دسته</option><?php foreach ( get_terms( array( 'taxonomy' => 'vetra_project_category', 'hide_empty' => false ) ) as $term ) : ?><option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $post ? wp_get_post_terms( $post->ID, 'vetra_project_category', array( 'fields' => 'ids' ) )[0] ?? 0 : 0, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option><?php endforeach; ?></select></label></p>
			<p><label>کارفرما<input type="text" name="project_client" value="<?php echo esc_attr( $value( 'project_client' ) ); ?>"></label></p>
			<p><label>موقعیت<input type="text" name="project_location" value="<?php echo esc_attr( $value( 'project_location' ) ); ?>"></label></p>
			<p><label>نوع پروژه<input type="text" name="project_type" value="<?php echo esc_attr( $value( 'project_type' ) ); ?>"></label></p>
			<p><label>سال اجرا<input type="text" name="project_year" value="<?php echo esc_attr( $value( 'project_year' ) ); ?>"></label></p>
			<p><label>وضعیت<select name="project_status"><option value="planning" <?php selected( $value( 'project_status' ), 'planning' ); ?>>در مرحله طراحی</option><option value="active" <?php selected( $value( 'project_status' ), 'active' ); ?>>در حال اجرا</option><option value="completed" <?php selected( $value( 'project_status' ), 'completed' ); ?>>تکمیل‌شده</option></select></label></p>
			<p><label>زیربنا / مساحت<input type="text" name="project_area" value="<?php echo esc_attr( $value( 'project_area' ) ); ?>"></label></p>
			<p><label>بودجه پروژه<input type="text" name="project_budget" value="<?php echo esc_attr( $value( 'project_budget' ) ); ?>"></label></p>
			<p><label>معمار / طراح<input type="text" name="project_architect" value="<?php echo esc_attr( $value( 'project_architect' ) ); ?>"></label></p>
			<p><label>مدیر پروژه<input type="text" name="project_manager" value="<?php echo esc_attr( $value( 'project_manager' ) ); ?>"></label></p>
			<p><label>تاریخ تحویل<input type="text" name="project_completion" value="<?php echo esc_attr( $value( 'project_completion' ) ); ?>"></label></p>
			<p><label>لینک پروژه<input type="url" name="project_website" value="<?php echo esc_attr( $value( 'project_website' ) ); ?>"></label></p>
			<p><label>تصویر شاخص پروژه<input type="file" name="project_image" accept="image/*"></label></p>
		</div>
		<p><label>خلاصه پروژه<textarea name="project_excerpt" rows="3"><?php echo esc_textarea( $post ? $post->post_excerpt : '' ); ?></textarea></label></p>
		<p><label>توضیحات کامل<textarea name="project_content" rows="7"><?php echo esc_textarea( $post ? $post->post_content : '' ); ?></textarea></label></p>
		<button class="vetra-button vetra-button--primary" type="submit"><?php echo $post ? 'ذخیره ویرایش' : 'ثبت پروژه'; ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'vetra_project_form', 'vetra_project_form_shortcode' );

function vetra_project_list_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'number' => 12 ), $atts, 'vetra_project_list' );
	$query = new WP_Query( array( 'post_type' => 'vetra_project', 'post_status' => 'publish', 'posts_per_page' => absint( $atts['number'] ) ) );
	ob_start();
	if ( $query->have_posts() ) {
		echo '<div class="vetra-project-grid vetra-project-grid--shortcode">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<article class="vetra-project-card">';
			if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { echo '<div class="vetra-project-card__art vetra-project-card__art--1"><span>' . vetra_inline_icon( 'building' ) . '</span></div>'; }
			echo '<div class="vetra-project-card__overlay"><span>' . esc_html( get_post_meta( get_the_ID(), '_project_location', true ) ) . '</span><h3>' . esc_html( get_the_title() ) . '</h3><a href="' . esc_url( get_permalink() ) . '">' . vetra_inline_icon( 'arrow-up' ) . '</a></div></article>';
		}
		echo '</div>';
	} else {
		echo '<p class="vetra-content-card">هنوز پروژه‌ای منتشر نشده است.</p>';
	}
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'vetra_project_list', 'vetra_project_list_shortcode' );

function vetra_roles_admin_menu() {
	add_users_page( 'نقش‌های کاربری وترا', 'نقش‌های وترا', 'manage_options', 'vetra-roles', 'vetra_roles_admin_page' );
}
add_action( 'admin_menu', 'vetra_roles_admin_menu' );

function vetra_roles_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$managed_caps = array( 'vetra_submit_projects' => 'ثبت پروژه از طریق شورتکد', 'read_project' => 'مشاهده پروژه', 'edit_project' => 'ویرایش پروژه‌های خود', 'delete_project' => 'حذف پروژه خود', 'edit_projects' => 'مدیریت پروژه‌ها', 'edit_others_projects' => 'ویرایش پروژه‌های دیگران', 'publish_projects' => 'انتشار پروژه', 'delete_projects' => 'حذف پروژه', 'upload_files' => 'آپلود رسانه' );
	$roles = wp_roles()->roles;
	$custom_roles = array_filter( $roles, function( $role, $slug ) { return 0 === strpos( $slug, 'vetra_' ); }, ARRAY_FILTER_USE_BOTH );
	?>
	<div class="wrap vetra-admin-wrap" dir="rtl">
		<h1>نقش‌های کاربری وترا</h1>
		<p>نقش‌های سفارشی بسازید و مشخص کنید کدام کاربران از طریق شورتکد <code>[vetra_project_form]</code> امکان ثبت یا ویرایش پروژه داشته باشند.</p>
		<div class="vetra-admin-card">
			<h2>ایجاد نقش جدید</h2>
			<form method="post">
				<?php wp_nonce_field( 'vetra_role_action', 'vetra_role_nonce' ); ?><input type="hidden" name="vetra_role_action" value="add">
				<p><label>نام نمایشی نقش <input class="regular-text" required name="role_name" type="text"></label> <label>شناسه نقش <input class="regular-text" required name="role_slug" type="text" pattern="[a-z0-9_-]+"></label></p>
				<?php foreach ( $managed_caps as $cap => $label ) : ?><label class="vetra-capability"><input type="checkbox" name="caps[]" value="<?php echo esc_attr( $cap ); ?>"> <?php echo esc_html( $label ); ?></label><?php endforeach; ?>
				<p><button class="button button-primary">افزودن نقش</button></p>
			</form>
		</div>
		<?php foreach ( $custom_roles as $slug => $role ) : ?>
		<div class="vetra-admin-card"><h2><?php echo esc_html( $role['name'] ); ?> <code><?php echo esc_html( $slug ); ?></code></h2><form method="post"><?php wp_nonce_field( 'vetra_role_action', 'vetra_role_nonce' ); ?><input type="hidden" name="vetra_role_action" value="update"><input type="hidden" name="role_slug" value="<?php echo esc_attr( $slug ); ?>"><p><label>نام نقش <input class="regular-text" required name="role_name" type="text" value="<?php echo esc_attr( $role['name'] ); ?>"></label></p><?php foreach ( $managed_caps as $cap => $label ) : ?><label class="vetra-capability"><input type="checkbox" name="caps[]" value="<?php echo esc_attr( $cap ); ?>" <?php checked( ! empty( $role['capabilities'][ $cap ] ) ); ?>> <?php echo esc_html( $label ); ?></label><?php endforeach; ?><p><button class="button button-primary">ذخیره نقش</button></p></form></div>
		<?php endforeach; ?>
		<p><strong>شورتکدهای آماده:</strong> <code>[vetra_project_form]</code> برای ثبت پروژه و <code>[vetra_project_list]</code> برای نمایش پروژه‌های منتشرشده.</p>
	</div>
	<?php
}

function vetra_roles_admin_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) || empty( $_POST['vetra_role_action'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vetra_role_nonce'] ?? '' ) ), 'vetra_role_action' ) ) {
		return;
	}
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
		foreach ( array( 'vetra_submit_projects', 'read_project', 'edit_project', 'delete_project', 'edit_projects', 'edit_others_projects', 'publish_projects', 'delete_projects', 'upload_files' ) as $cap ) {
			if ( isset( $caps[ $cap ] ) ) { $role->add_cap( $cap ); } else { $role->remove_cap( $cap ); }
		}
	}
	wp_safe_redirect( admin_url( 'users.php?page=vetra-roles' ) );
	exit;
}
add_action( 'admin_init', 'vetra_roles_admin_actions' );

function vetra_project_admin_assets( $hook ) {
	$is_roles_page = 'users_page_vetra-roles' === $hook;
	$is_project_page = isset( $_GET['post_type'] ) && 'vetra_project' === sanitize_key( wp_unslash( $_GET['post_type'] ) );
	if ( $is_roles_page || $is_project_page || 'post.php' === $hook || 'post-new.php' === $hook ) {
		wp_enqueue_style( 'vetra-project-admin', VETRA_PORTAL_URI . '/assets/css/project-admin.css', array(), VETRA_PORTAL_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'vetra_project_admin_assets' );
