<?php

declare(strict_types=1);

namespace WPLogjar;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

final class Debugger implements LoggerAwareInterface
{
	use LoggerAwareTrait;

	/** @var int */
	protected $backtraceLevel = 0;

	/** @var array */
	protected $debugLines = [];

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function __destruct()
	{
		$this->flushDebugLines();
	}

	public function setBacktraceLevel(int $level): void
	{
		$this->backtraceLevel = $level;
	}

	public function getBacktraceLevel(): int
	{
		return $this->backtraceLevel;
	}

	public function debug(): self
	{
		$args = func_get_args();
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$line = $backtrace[$this->backtraceLevel]['file'] . ':' . $backtrace[$this->backtraceLevel]['line'];

		foreach ($args as $var) {
			$this->debugLines[$line][] = var_export($var, true);
		}

		return $this;
	}

	protected function flushDebugLines(): void
	{
		foreach ($this->debugLines as $line => $vars) {

			$data = implode("\n", $vars);
			$this->logger->debug("$line\n$data");
		}

		$this->debugLines = [];
	}
}
