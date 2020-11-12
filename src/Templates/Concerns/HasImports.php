<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\Templates\UseStatement;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasImports
{
	/** @var \Shomisha\Stubless\Templates\UseStatement[] */
	protected array $imports = [];

	public function addImport(UseStatement $import): self
	{
		$this->imports[$import->getName()] = $import;
	}

	public function removeImport(string $name): self
	{
		unset($this->imports[$name]);

		return $this;
	}

	/** @param \Shomisha\Stubless\Templates\UseStatement[] $imports */
	public function withImports(array $imports): self
	{
		$this->validateArrayElements($imports, UseStatement::class);

		$this->imports = $imports;

		return $this;
	}

	protected function addImportsToDeclaration(Declaration $declaration): void
	{
		foreach ($this->imports as $import) {
			$declaration->addStmt($import->constructNode());
		}
	}
}