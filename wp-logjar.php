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

use WPLogjar\WPLogjar;

use const WPLogjar\PLUGIN_SLUG;

defined('ABSPATH') || die();

define('WPLogjar\PLUGIN_VERSION', '1.0.0');
define('WPLogjar\PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WPLogjar\PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPLogjar\PLUGIN_SLUG', pathinfo(__FILE__, PATHINFO_FILENAME));
define('WPLogjar\MENU_SLUG', PLUGIN_SLUG);

require_once __DIR__ . '/vendor/autoload.php';

WPLogjar::getInstance();

if (!function_exists('logjar')) {

	function logjar(): void
	{
		$args = func_get_args();
		$debugger = WPLogjar::getInstance()->getDebugger();
		$level = $debugger->getBacktraceLevel();

		$debugger->setBacktraceLevel($level + 1);
		$debugger->debug(...$args);
		$debugger->setBacktraceLevel($level);
	}
}
