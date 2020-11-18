<?php

namespace Shomisha\Stubless\Formatters;

use Shomisha\Stubless\Contracts\Formatter;

class CsFixerFormatter implements Formatter
{
	private string $csFixerPath = __DIR__ . '/../../dist/php-cs-fixer';

	private array $rules = [
		'class_attributes_separation',
		'no_leading_import_slash',
		'ordered_imports',
		'single_line_after_imports',
	];

	public function format(string $code): string
	{
		$tempPath = $this->storeCodeAsTemp($code);

		$this->runFixer($tempPath);

		$formattedCode = $this->getCodeFromTemp($tempPath);

		$this->removeTemp($tempPath);

		return $formattedCode;
	}

	private function storeCodeAsTemp(string $code): string
	{
		$tempFile = tempnam(sys_get_temp_dir(), 'format_');

		file_put_contents($tempFile, $code);

		return $tempFile;
	}

	private function runFixer(string $tempPath): void
	{
		exec($this->prepareFixerCommand($tempPath) . " 2>&1");
	}

	private function prepareFixerCommand(string $path): string
	{
		$rules = implode(',', $this->rules);

		return "{$this->csFixerPath} fix \"{$path}\" --rules={$rules} --use-cache=no";
	}

	private function removeTemp(string $tempPath): void
	{
		unlink($tempPath);
	}

	private function getCodeFromTemp(string $tempPath): string
	{
		return file_get_contents($tempPath);
	}
}