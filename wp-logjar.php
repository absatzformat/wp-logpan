<?php

/**
 * Plugin Name: WP Logjar
 * Plugin URI: https://github.com/logjar/wp-logjar
 * Description: Send logs to Logjar.
 * Author: Logjar
 * Version: 1.0.0
 * Author URI: https://github.com/logjar
 * Text Domain: logjar
 */

declare(strict_types=1);

use Logjar\Logger\Logger;
use Logjar\Logger\SocketHandler;
use WPLogjar\WPLogjar;

use const WPLogjar\MENU_SLUG;
use const WPLogjar\PLUGIN_SLUG;

defined('ABSPATH') || die();

define('WPLogjar\PLUGIN_VERSION', '1.0.0');
define('WPLogjar\PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WPLogjar\PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPLogjar\PLUGIN_SLUG', pathinfo(__FILE__, PATHINFO_FILENAME));
define('WPLogjar\MENU_SLUG', PLUGIN_SLUG);

require_once __DIR__ . '/vendor/autoload.php';

WPLogjar::getInstance(true)->setLogger(new Logger(new SocketHandler(
	'http://192.168.178.44:8080',
	2,
	'1234'
)));

if (!function_exists('lp_debug')) {

	function lp_debug(): void
	{
		$args = func_get_args();
		$logjar = WPLogjar::getInstance();
		$level = $logjar->getBacktraceLevel();

		$logjar->setBacktraceLevel($level + 1);
		$logjar->debug(...$args);
		$logjar->setBacktraceLevel($level);
	}
}

add_action('shutdown', [WPLogjar::getInstance(), 'flushDebugLines']);


add_action('admin_menu', function () {
	add_options_page(__('Logjar', 'logjar'), __('Logjar', 'logjar'), 'manage_options', MENU_SLUG, function () {
		include __DIR__ . '/views/logjar-settings.php';
	});
});
