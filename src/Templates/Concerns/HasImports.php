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

	/** @param \Shomisha\Stubless\Templates\UseStatement[] */
	public function imports(array $imports = null)
	{
		if ($imports === null) {
			return $this->getImports();
		}

		return $this->withImports($imports);
	}

	public function addImportable(Importable $importable): self
	{
		return $this->addImport($importable->getImportStatement());
	}

	public function addImport(UseStatement $import): self
	{
		$this->imports[$import->getName()] = $import;

		return $this;
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

		$this->imports = [];

		foreach ($imports as $import) {
			$this->addImport($import);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\Templates\UseStatement[] */
	public function getImports(): array
	{
		return $this->imports;
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