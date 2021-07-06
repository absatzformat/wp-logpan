<?php

declare(strict_types=1);

namespace WPLogjar;

use Logjar\Logger\HandlerInterface;

final class FileWriter implements HandlerInterface
{
	/** @var string */
	protected $filepath;

	public function __construct(string $filepath)
	{
		$this->filepath = $filepath;
	}

	public function handle(array $record): void
	{
		if (is_file($this->filepath)) {

			$data = var_export($record, true) . "\n";
			@file_put_contents($this->filepath, $data, FILE_APPEND);
		}
	}
}
