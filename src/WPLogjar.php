<?php

declare(strict_types=1);

namespace WPLogjar;

use Logjar\Logger\Logger;
use Logjar\Logger\SocketHandler;
use Psr\Log\LoggerInterface;

final class WPLogjar
{
	/** @var null|self */
	protected static $instance;

	/** @var LoggerInterface */
	protected $logger;

	/** @var Debugger */
	protected $debugger;

	/** @var ErrorHandler */
	protected $errorHandler;

	protected function __construct()
	{
		$options = get_option('logjar_options');

		$handler = new SocketHandler(
			($options['server_address'] ?? '') . ':' . ($options['server_port'] ?? ''),
			(int)($options['log_channel'] ?? 1),
			($options['server_token'] ?? ''),
			empty($options['log_path']) ? '/channel' : $options['log_path']
		);

		// var_dump($handler);

		// $handler = new FileWriter(__DIR__ . '/debug.log');

		$this->logger = new Logger($handler);

		$this->debugger = new Debugger($this->logger);
		$this->errorHandler = new ErrorCatcher($this->logger);

		if (is_admin()) {

			add_action('admin_menu', [$this, 'adminMenu']);
			add_action('admin_init', [$this, 'adminInit']);
		}
	}

	public function getDebugger(): Debugger
	{
		return $this->debugger;
	}

	public function adminMenu(): void
	{
		add_options_page(
			__('Logjar Logger', 'logjar'),
			__('Logjar Logger', 'logjar'),
			'manage_options',
			PLUGIN_SLUG,
			[$this, 'displayOptionsPage']
		);
	}

	public function adminInit(): void
	{
		register_setting('logjar_options', 'logjar_options', [$this, 'optionsValidate']);
	}

	public function optionsValidate($input)
	{
		$options = get_option('logjar_options');

		if (isset($input['server_address'])) {
			$options['server_address'] = $input['server_address'];
		}

		if (isset($input['server_address'])) {
			$options['server_address'] = $input['server_address'];
		}

		if (isset($input['server_port'])) {
			$options['server_port'] = $input['server_port'];
		}

		if (isset($input['server_token'])) {
			$options['server_token'] = $input['server_token'];
		}

		if (isset($input['log_path'])) {
			$options['log_path'] = $input['log_path'];
		}

		if (isset($input['log_channel'])) {
			$options['log_channel'] = (int)$input['log_channel'];
		}

		if (isset($input['log_level'])) {
			$options['log_level'] = (int)$input['log_level'];
		}

		return $options;
	}

	public function displayOptionsPage(): void
	{
		$options = get_option('logjar_options');

		include __DIR__ . '/../views/logjar-settings.php';
	}

	public static function getInstance(): self
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
