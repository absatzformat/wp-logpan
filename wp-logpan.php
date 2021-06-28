<?php

/**
 * Plugin Name: WP LogPan
 * Plugin URI: https://github.com/absatzformat/wp-logpan
 * Description: Send logs to LogPan
 * Author: Absatzformat GmbH
 * Version: 1.0.0
 * Author URI: https://absatzformat.de
 */

declare(strict_types=1);

use Absatzformat\WPLogPan\LogAdaptor;
use LogPan\Logger\Logger;

defined('ABSPATH') || die();

define('Absatzformat\WPLogPan\PLUGIN_VERSION', '1.0.0');
define('Absatzformat\WPLogPan\PLUGIN_PATH', plugin_dir_path(__FILE__));
define('Absatzformat\WPLogPan\PLUGIN_URL', plugin_dir_url(__FILE__));
define('Absatzformat\WPLogPan\PLUGIN_SLUG', pathinfo(__FILE__, PATHINFO_FILENAME));
define('Absatzformat\WPLogPan\MENU_SLUG', Absatzformat\WPLogPan\PLUGIN_SLUG);

require_once __DIR__ . '/vendor/autoload.php';

LogAdaptor::getInstance()->setLogger(new Logger(
	'',
	1
));
