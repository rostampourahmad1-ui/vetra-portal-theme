<?php
/**
 * Clean-room settings migration utilities.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Migration {
	public static function import_file( string $path ) {
		if ( ! is_readable( $path ) ) {
			return new \WP_Error( 'vetra_plus_backup_missing', 'فایل پشتیبان قابل خواندن نیست.' );
		}
		if ( filesize( $path ) > 5 * MB_IN_BYTES ) {
			return new \WP_Error( 'vetra_plus_backup_too_large', 'حجم فایل پشتیبان بیش از حد مجاز است.' );
		}
		$payload = file_get_contents( $path );
		return self::import_json( false === $payload ? '' : $payload );
	}

	public static function import_json( string $payload ) {
		$data = json_decode( $payload, true );
		if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
			return new \WP_Error( 'vetra_plus_backup_invalid_json', 'فقط فایل JSON استاندارد و معتبر پذیرفته می‌شود.' );
		}
		$source = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : $data;
		$fields = SettingsSchema::flat_fields();
		$mapped = array();
		$ignored = array();
		foreach ( $source as $legacy_key => $value ) {
			$new_key = self::map_key( (string) $legacy_key );
			if ( ! isset( $fields[ $new_key ] ) ) {
				$ignored[] = sanitize_key( (string) $legacy_key );
				continue;
			}
			$mapped[ $new_key ] = self::sanitize_value( $value, $fields[ $new_key ] );
		}
		if ( ! $mapped ) {
			return new \WP_Error( 'vetra_plus_backup_no_supported_keys', 'هیچ کلید پشتیبانی‌شده‌ای در فایل پیدا نشد.' );
		}
		return array( 'settings' => array_merge( SettingsSchema::defaults(), $mapped ), 'mapped_keys' => array_keys( $mapped ), 'ignored_keys' => $ignored );
	}

	public static function map_key( string $legacy_key ): string {
		$key = sanitize_key( $legacy_key );
		$key = preg_replace( '/^(theme_mods_|option_|options_)/', '', $key );
		if ( 0 === strpos( $key, 'vetra_plus_' ) ) {
			$key = 'vetra_' . substr( $key, 11 );
		}
		return 0 === strpos( $key, 'vetra_' ) ? $key : 'vetra_' . $key;
	}

	private static function sanitize_value( $value, array $field ) {
		switch ( $field['type'] ) {
			case 'boolean': return ! empty( $value );
			case 'integer': return max( $field['min'], min( $field['max'], absint( $value ) ) );
			case 'color': return sanitize_hex_color( (string) $value ) ?: $field['default'];
			case 'url': return esc_url_raw( (string) $value );
			case 'email': return sanitize_email( (string) $value );
			case 'slug': return sanitize_title( (string) $value );
			case 'css': return wp_strip_all_tags( (string) $value );
			default: return sanitize_text_field( (string) $value );
		}
	}
}
