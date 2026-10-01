<?php
/**
 * Plugin Name: Vetra Plus
 * Plugin URI: https://vetragroup.ir/
 * Description: هستهٔ مستقل داده و قابلیت‌های بیزنسی وترا؛ بدون وابستگی به پوسته یا فریم‌ورک سنگین.
 * Version: 1.0.0
 * Requires at least: 6.3
 * Requires PHP: 8.2
 * Author: Vetra Group
 * License: GPL-2.0-or-later
 * Text Domain: vetra-plus
 * Domain Path: /languages
 *
 * @package VetraPlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VETRA_PLUS_VERSION', '1.0.0' );
define( 'VETRA_PLUS_FILE', __FILE__ );
define( 'VETRA_PLUS_DIR', plugin_dir_path( __FILE__ ) );
define( 'VETRA_PLUS_URL', plugin_dir_url( __FILE__ ) );

require_once VETRA_PLUS_DIR . 'includes/class-loader.php';

\Vetra\Plus\Loader::register();
\Vetra\Plus\Plugin::boot();
