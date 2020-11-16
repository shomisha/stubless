<?php

namespace Shomisha\Stubless\Utilities;

use Shomisha\Stubless\Templates\UseStatement;

class Importable
{
	private string $fqcn;

	private string $baseName;

	private ?string $alias;

	private UseStatement $use;

	public function __construct(string $fullName, string $alias = null)
	{
		$this->fqcn = $fullName;
		$this->alias = $alias;
		$this->baseName = $this->guessBaseName($fullName);
		$this->use = new UseStatement($fullName, $alias);
	}

	public function getFullName(): string
	{
		return $this->fqcn;
	}

	public function getImportStatement(): UseStatement
	{
		return $this->use;
	}

	public function getShortName(): string
	{
		return $this->alias ?? $this->baseName;
	}

	private function guessBaseName(string $fullName): string
	{
		$exploded = explode('\\', $fullName);

		return array_pop($exploded);
	}
}