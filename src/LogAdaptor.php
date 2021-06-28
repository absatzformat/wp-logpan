<?php

declare(strict_types=1);

namespace Absatzformat\WPLogPan;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

final class LogAdaptor implements LoggerAwareInterface
{
	/** @var null|self */
	protected static $instance;

	/** @var null|LoggerInterface */
	protected $logger;

	protected function __construct()
	{
		set_error_handler([$this, 'errorHandler']);
	}

	public static function getInstance(): self
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function setLogger(LoggerInterface $logger): void
	{
		$this->logger = $logger;
	}

	public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): bool
	{
		$error = [
			'type' => $errno,
			'message' => $errstr,
			'file' => $errfile,
			'line' => $errline
		];

		// TODO: log

		return false;
	}
}
