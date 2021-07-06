<?php

declare(strict_types=1);

namespace WPLogjar;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Throwable;

final class ErrorCatcher implements LoggerAwareInterface
{
	use LoggerAwareTrait;

	/** @var null|callable */
	protected $prevErrorHandler;

	/** @var null|callable */
	protected $prevExceptionHandler;

	/** @var array<int, string> */
	protected $errorConstants;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
		$this->errorConstants = $this->getErrorConstants();
		$this->registerHandlers();
	}

	protected static function getErrorConstants(): array
	{
		$constants = get_defined_constants(true)['Core'];
		$constants = array_slice($constants, 0, 16, true);

		return array_flip($constants);
	}

	protected function registerHandlers(): void
	{
		$this->prevErrorhandler = set_error_handler([$this, 'errorHandler']);
		$this->prevExceptionHandler = set_exception_handler([$this, 'exceptionHandler']);

		register_shutdown_function([$this, 'shutdownHandler']);
	}

	public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): bool
	{
		// TODO: process error

		$context = [
			'type' => $this->errorConstants[$errno],
			'message' => $errstr,
			'file' => $errfile,
			'line' => $errline
		];

		// TODO: check error type
		$this->logger->error('{type}: {message} in {file} on line {line}', $context);

		if ($this->prevErrorHandler) {
			($this->prevErrorHandler)($errno, $errstr, $errfile, $errline);
		}

		return false;
	}

	public function shutdownHandler(): void
	{

		$error = error_get_last();

		if ($error) {
		}

		// TODO: flush stored records
	}

	public function exceptionHandler(Throwable $exception): void
	{
		// TODO: process exception

		if ($this->prevExceptionHandler) {
			($this->prevExceptionHandler)($exception);
		}
	}
}
