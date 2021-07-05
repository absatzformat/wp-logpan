<?php

declare(strict_types=1);

namespace WPLogjar;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

final class WPLogjar implements LoggerAwareInterface
{
	use LoggerAwareTrait;

	/** @var null|self */
	protected static $instance;

	/** @var int */
	protected $backtraceLevel = 0;

	/** @var bool */
	protected $logErrors;

	/** @var array<int, string> */
	protected $errorConstants;

	/** @var array */
	protected $debugLines = [];

	protected function __construct(bool $logErrors)
	{
		$this->logErrors = $logErrors;
		$this->errorConstants = $this->getErrorConstants();

		set_error_handler([$this, 'errorHandler']);
	}

	protected static function getErrorConstants(): array
	{
		$constants = get_defined_constants(true)['Core'];
		$constants = array_slice($constants, 0, 16, true);

		return array_flip($constants);
	}

	public static function getInstance(bool $logErrors = false): self
	{
		if (self::$instance === null) {
			self::$instance = new self($logErrors);
		}

		return self::$instance;
	}

	public function setBacktraceLevel(int $level): void
	{
		$this->backtraceLevel = $level;
	}

	public function getBacktraceLevel(): int
	{
		return $this->backtraceLevel;
	}

	public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): bool
	{
		if ($this->logErrors && $this->logger) {

			$context = [
				'type' => $this->errorConstants[$errno],
				'message' => $errstr,
				'file' => $errfile,
				'line' => $errline
			];

			$this->logger->error('{type}: {message} in {file} on line {line}', $context);
		}

		return false;
	}

	public function debug(): void
	{
		$args = func_get_args();
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$line = $backtrace[$this->backtraceLevel]['file'] . ':' . $backtrace[$this->backtraceLevel]['line'];

		foreach ($args as $var) {
			$this->debugLines[$line][] = var_export($var, true);
		}
	}

	public function flushDebugLines(): void
	{
		if ($this->logger) {

			foreach ($this->debugLines as $line => $vars) {

				$data = implode("\n", $vars);
				$this->logger->debug("$line\n$data");
			}

			$this->debugLines = [];
		}
	}
}
