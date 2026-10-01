<?php
/** GitHub release checker for stable WordPress theme updates. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Vetra_Portal_GitHub_Updater {
	const THEME_SLUG = 'vetra-portal-theme';
	const REPOSITORY = 'rostampourahmad1-ui/vetra-portal-theme';

	public static function boot() {
		add_filter( 'pre_set_site_transient_update_themes', array( __CLASS__, 'inject_update' ) );
		add_action( 'admin_notices', array( __CLASS__, 'notice' ) );
		add_filter( 'upgrader_source_selection', array( __CLASS__, 'fix_source_directory' ), 10, 4 );
	}

	/** Normalize GitHub's extracted directory so WordPress updates the existing theme. */
	public static function fix_source_directory( $source, $remote_source, $upgrader, $hook_extra = array() ) {
		global $wp_filesystem;
		if ( empty( $hook_extra['theme'] ) || self::THEME_SLUG !== $hook_extra['theme'] ) { return $source; }
		$correct = trailingslashit( $remote_source ) . self::THEME_SLUG . '/';
		if ( untrailingslashit( $source ) === untrailingslashit( $correct ) ) { return $source; }
		if ( ! $wp_filesystem || ! $wp_filesystem->move( $source, $correct ) ) { return $source; }
		return $correct;
	}

	public static function inject_update( $transient ) {
		if ( ! is_object( $transient ) ) { $transient = new stdClass(); }
		if ( empty( $transient->checked ) ) { return $transient; }
		$release = self::latest_release();
		$current = isset( $transient->checked[ self::THEME_SLUG ] ) ? $transient->checked[ self::THEME_SLUG ] : VETRA_PORTAL_VERSION;
		if ( ! $release || empty( $release['version'] ) || version_compare( $release['version'], $current, '<=' ) ) { return $transient; }
		$transient->response[ self::THEME_SLUG ] = array(
			'theme' => self::THEME_SLUG,
			'new_version' => $release['version'],
			'url' => $release['url'],
			'package' => $release['package'],
			'requires' => '6.0',
			'requires_php' => '8.2',
		);
		return $transient;
	}

	private static function latest_release() {
		$cache_key = 'vetra_portal_latest_release';
		$cached = get_transient( $cache_key );
		if ( false !== $cached ) { return is_array( $cached ) && ! empty( $cached ) ? $cached : false; }
		$response = wp_remote_get( 'https://api.github.com/repos/' . self::REPOSITORY . '/releases/latest', array(
			'timeout' => 8,
			'headers' => array(
			'Accept' => 'application/vnd.github+json',
			'User-Agent' => 'VETRA-Portal-Updater/' . ( defined( 'VETRA_PORTAL_VERSION' ) ? VETRA_PORTAL_VERSION : '0' ),
			),
		) );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) { set_transient( $cache_key, array(), HOUR_IN_SECONDS ); return false; }
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		$version = ltrim( sanitize_text_field( isset( $data['tag_name'] ) ? $data['tag_name'] : '' ), 'v' );
		$package = '';
		$asset_names = array( 'vetra.zip', self::THEME_SLUG . '-' . $version . '.zip', self::THEME_SLUG . '-v' . $version . '.zip' );
		foreach ( (array) ( isset( $data['assets'] ) ? $data['assets'] : array() ) as $asset ) {
			if ( in_array( isset( $asset['name'] ) ? $asset['name'] : '', $asset_names, true ) ) { $package = esc_url_raw( isset( $asset['browser_download_url'] ) ? $asset['browser_download_url'] : '' ); break; }
		}
		if ( ! $package ) { $package = esc_url_raw( isset( $data['zipball_url'] ) ? $data['zipball_url'] : '' ); }
		$result = $version && $package ? array(
			'version' => $version,
			'url' => esc_url_raw( isset( $data['html_url'] ) ? $data['html_url'] : '' ),
			'package' => $package,
		) : array();
		set_transient( $cache_key, $result, 6 * HOUR_IN_SECONDS );
		return $result ? $result : false;
	}

	public static function notice() {
		if ( ! current_user_can( 'update_themes' ) ) { return; }
		$updates = get_site_transient( 'update_themes' );
		if ( empty( $updates->response[ self::THEME_SLUG ] ) ) { return; }
		$update = $updates->response[ self::THEME_SLUG ];
		$message = sprintf( __( 'نسخه %s پوسته VETRA Portal در دسترس است.', 'vetra-portal-theme' ), esc_html( $update['new_version'] ) );
		echo '<div class="notice notice-info is-dismissible"><p>' . esc_html( $message ) . ' <a href="' . esc_url( admin_url( 'themes.php' ) ) . '">' . esc_html__( 'مشاهده به‌روزرسانی‌ها', 'vetra-portal-theme' ) . '</a></p></div>';
	}
}
Vetra_Portal_GitHub_Updater::boot();
