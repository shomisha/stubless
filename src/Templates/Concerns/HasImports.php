<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\Templates\UseStatement;
use Shomisha\Stubless\Utilities\Importable;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasImports
{
	/** @var \Shomisha\Stubless\Templates\UseStatement[] */
	protected array $imports = [];

	public function getImports(): array
	{
		return $this->imports;
	}

	public function addImport(UseStatement $import): self
	{
		$this->imports[$import->getName()] = $import;

		return $this;
	}

	public function addImportable(Importable $importable): self
	{
		return $this->addImport($importable->getImportStatement());
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

		$rekeyedImports = [];
		foreach ($imports as $import) {
			$rekeyedImports[$import->getName()] = $import;
		}

		$this->imports = $rekeyedImports;

		return $this;
	}

	protected function isImportable($value): bool
	{
		return $value instanceof Importable;
	}

	/** @param \Shomisha\Stubless\Contracts\DelegatesImports[] $delegates */
	protected function gatherImportsFromDelegates(array $delegates): array
	{
		$imports = [];

		foreach ($delegates as $delegate) {
			$imports = array_merge($imports, $delegate->getDelegatedImports());
		}

		return $imports;
	}
}