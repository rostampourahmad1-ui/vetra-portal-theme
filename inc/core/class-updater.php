<?php
/** GitHub release checker for stable WordPress theme updates. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Vetra_Portal_GitHub_Updater {
	const THEME_SLUG = 'vetra-portal-theme';
	const REPOSITORY = 'rostampourahmad1-ui/vetra-portal-theme';
	public static function boot() {
		add_filter( 'pre_set_site_transient_update_themes', array( __CLASS__, 'inject_update' ) );
		add_action( 'admin_notices', array( __CLASS__, 'notice' ) );
	}
	public static function inject_update( $transient ) {
		if ( ! is_object( $transient ) ) { $transient = new std_class(); }
		if ( empty( $transient->checked ) || ! isset( $transient->checked[ self::THEME_SLUG ] ) ) { return $transient; }
		$release = self::latest_release();
		if ( ! $release || empty( $release['version'] ) || version_compare( VETRA_PORTAL_VERSION, $release['version'], '>=' ) ) { return $transient; }
		$transient->response[ self::THEME_SLUG ] = array(
			'theme' => self::THEME_SLUG, 'new_version' => $release['version'], 'url' => $release['url'],
			'package' => $release['package'],
		);
		return $transient;
	}
	private static function latest_release() {
		$cached = get_site_transient( 'vetra_portal_latest_release' );
		if ( false !== $cached ) { return is_array( $cached ) ? $cached : false; }
		$response = wp_remote_get( 'https://api.github.com/repos/' . self::REPOSITORY . '/releases/latest', array( 'timeout' => 8, 'headers' => array( 'Accept' => 'application/vnd.github+json', 'User-Agent' => self::THEME_SLUG ) ) );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) { set_site_transient( 'vetra_portal_latest_release', array(), HOUR_IN_SECONDS ); return false; }
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		$tag = sanitize_text_field( $data['tag_name'] ?? '' ); $version = ltrim( $tag, 'v' );
		$result = $version && ! empty( $data['zipball_url'] ) ? array( 'version' => $version, 'url' => esc_url_raw( $data['html_url'] ?? '' ), 'package' => esc_url_raw( $data['zipball_url'] ) ) : array();
		set_site_transient( 'vetra_portal_latest_release', $result, 6 * HOUR_IN_SECONDS ); return $result ?: false;
	}
	public static function notice() {
		if ( ! current_user_can( 'update_themes' ) ) { return; }
		$updates = get_site_transient( 'update_themes' );
		if ( ! empty( $updates->response[ self::THEME_SLUG ] ) ) { $update = $updates->response[ self::THEME_SLUG ]; echo '<div class="notice notice-info is-dismissible"><p>' . esc_html( sprintf( __( 'نسخه %s پوسته VETRA Portal در دسترس است.', 'vetra-portal-theme' ), $update['new_version'] ) ) . ' <a href="' . esc_url( admin_url( 'themes.php' ) ) . '">' . esc_html__( 'مشاهده به‌روزرسانی‌ها', 'vetra-portal-theme' ) . '</a></p></div>'; }
	}
}
Vetra_Portal_GitHub_Updater::boot();
