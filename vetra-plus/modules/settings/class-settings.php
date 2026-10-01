<?php
/**
 * Vetra Plus Theme Options panel.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Settings {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_post_vetra_plus_settings_export', array( $this, 'export' ) );
		add_action( 'admin_post_vetra_plus_settings_import', array( $this, 'import' ) );
		add_action( 'admin_post_vetra_plus_settings_reset', array( $this, 'reset' ) );
	}

	public static function get(): array {
		return wp_parse_args( get_option( 'vetra_plus_settings', array() ), SettingsSchema::defaults() );
	}

	public static function sanitize( $values ): array {
		$values = is_array( $values ) ? $values : array();
		$out = SettingsSchema::defaults();
		foreach ( SettingsSchema::flat_fields() as $key => $field ) {
			if ( ! array_key_exists( $key, $values ) ) {
				if ( 'boolean' === $field['type'] ) {
					$out[ $key ] = false;
				}
				continue;
			}
			$out[ $key ] = Migration::import_json( wp_json_encode( array( 'settings' => array( $key => $values[ $key ] ) ) ) );
			if ( is_wp_error( $out[ $key ] ) ) {
				$out[ $key ] = $field['default'];
			} else {
				$out[ $key ] = $out[ $key ]['settings'][ $key ];
			}
		}
		do_action( 'vetra_plus_settings_saved', $out );
		return $out;
	}

	public function register() {
		register_setting( 'vetra_plus_settings_group', 'vetra_plus_settings', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize' ), 'default' => SettingsSchema::defaults() ) );
	}

	public function menu() {
		add_menu_page( 'تنظیمات Vetra Plus', 'Vetra Plus', 'manage_options', 'vetra-plus-settings', array( $this, 'render' ), 'dashicons-admin-customizer', 58 );
	}

	public function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی کافی ندارید.', 'vetra-plus' ) );
		}
		$settings = self::get();
		?><div class="wrap" dir="rtl"><h1><?php esc_html_e( 'تنظیمات Vetra Plus', 'vetra-plus' ); ?></h1><p><?php esc_html_e( 'تنظیمات مستقل، سبک و قابل بازگشت برای لایهٔ ارائهٔ وترا.', 'vetra-plus' ); ?></p><form method="post" action="options.php"><?php settings_fields( 'vetra_plus_settings_group' ); ?><?php foreach ( SettingsSchema::categories() as $category => $label ) : ?><section class="vetra-plus-settings-section"><h2><?php echo esc_html( $label ); ?></h2><?php foreach ( SettingsSchema::fields()[ $category ] as $key => $field ) : $this->field( $key, $field, $settings[ $key ] ?? $field['default'] ); endforeach; ?></section><?php endforeach; ?><?php submit_button( 'ذخیره تنظیمات' ); ?></form><hr><h2>پشتیبان و مهاجرت</h2><p>فقط JSON استاندارد و کلیدهای شناخته‌شده پذیرفته می‌شوند؛ فایل PHP یا serialized اجرا نمی‌شود.</p><p><a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=vetra_plus_settings_export' ), 'vetra_plus_settings_export' ) ); ?>">دانلود JSON</a></p><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data"><?php wp_nonce_field( 'vetra_plus_settings_import' ); ?><input type="hidden" name="action" value="vetra_plus_settings_import"><input type="file" name="vetra_plus_settings_file" accept="application/json,.json" required><button class="button button-primary" type="submit">درون‌ریزی JSON</button></form><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><?php wp_nonce_field( 'vetra_plus_settings_reset' ); ?><input type="hidden" name="action" value="vetra_plus_settings_reset"><button class="button" type="submit">بازنشانی تنظیمات</button></form></div><?php
	}

	private function field( $key, array $field, $value ) {
		$id = esc_attr( $key );
		echo '<p><label for="' . $id . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';
		if ( 'boolean' === $field['type'] ) {
			echo '<input id="' . $id . '" name="vetra_plus_settings[' . $id . ']" type="checkbox" value="1" ' . checked( $value, true, false ) . '>'; 
		} elseif ( 'textarea' === $field['type'] || 'css' === $field['type'] ) {
			echo '<textarea class="large-text" rows="5" id="' . $id . '" name="vetra_plus_settings[' . $id . ']">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input class="regular-text" id="' . $id . '" name="vetra_plus_settings[' . $id . ']" type="' . esc_attr( 'integer' === $field['type'] ? 'number' : ( 'color' === $field['type'] ? 'text' : 'text' ) ) . '" value="' . esc_attr( $value ) . '">';
		}
		echo '</p>';
	}

	public function export() {
		$this->authorize( 'vetra_plus_settings_export' );
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=vetra-plus-settings.json' );
		echo wp_json_encode( array( 'plugin' => 'vetra-plus', 'schema_version' => SettingsSchema::version(), 'exported_at' => gmdate( 'c' ), 'settings' => self::get() ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
		exit;
	}

	public function import() {
		$this->authorize( 'vetra_plus_settings_import' );
		$file = $_FILES['vetra_plus_settings_file'] ?? array();
		if ( empty( $file['tmp_name'] ) || ! empty( $file['error'] ) || (int) ( $file['size'] ?? 0 ) > 5 * MB_IN_BYTES ) {
			wp_die( esc_html__( 'فایل JSON معتبر نیست.', 'vetra-plus' ) );
		}
		$result = Migration::import_file( $file['tmp_name'] );
		if ( is_wp_error( $result ) ) {
			wp_die( esc_html( $result->get_error_message() ) );
		}
		$current = self::get();
		$mapped  = array_intersect_key( $result['settings'], array_flip( $result['mapped_keys'] ) );
		update_option( 'vetra_plus_settings', array_merge( $current, $mapped ), false );
		wp_safe_redirect( add_query_arg( 'vetra_notice', 'imported', admin_url( 'admin.php?page=vetra-plus-settings' ) ) );
		exit;
	}

	public function reset() {
		$this->authorize( 'vetra_plus_settings_reset' );
		update_option( 'vetra_plus_settings', SettingsSchema::defaults(), false );
		wp_safe_redirect( add_query_arg( 'vetra_notice', 'reset', admin_url( 'admin.php?page=vetra-plus-settings' ) ) );
		exit;
	}

	private function authorize( $action ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی کافی ندارید.', 'vetra-plus' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( $action );
	}
}
