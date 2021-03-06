<?php

namespace Shomisha\Stubless\Formatters;

use Shomisha\Stubless\Contracts\Formatter;

class CsFixerFormatter implements Formatter
{
	private string $csFixerPath = __DIR__ . '/../../dist/php-cs-fixer';

	private array $rules = [
		'class_attributes_separation' => true,
		'no_leading_import_slash' => true,
		'ordered_imports' => true,
		'single_line_after_imports' => true,
		'blank_line_before_statement' => true,
		'array_indentation' => true,
		'method_chaining_indentation' => true,
		'align_multiline_comment' => ['comment_type' => 'all_multiline'],
		'no_empty_phpdoc' => true,
		'phpdoc_indent' => true,
		'array_syntax' => ['syntax' => 'short'],
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
		$rules = json_encode($this->rules);

		return "\"{$this->csFixerPath}\" fix \"{$path}\" --rules='{$rules}' --using-cache=no";
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