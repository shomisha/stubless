<?php

namespace Shomisha\Stubless\Concerns;

use Shomisha\Stubless\ImperativeCode\UseStatement;
use Shomisha\Stubless\Utilities\Importable;

/** @mixin \Shomisha\Stubless\Abstractions\DeclarativeCode */
trait HasImports
{
	/** @var \Shomisha\Stubless\ImperativeCode\UseStatement[] */
	protected array $imports = [];

	/** @param \Shomisha\Stubless\ImperativeCode\UseStatement[] */
	public function imports(array $imports = null)
	{
		if ($imports === null) {
			return $this->getImports();
		}

		return $this->withImports($imports);
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

	/** @param \Shomisha\Stubless\ImperativeCode\UseStatement[] $imports */
	public function withImports(array $imports): self
	{
		$this->validateArrayElements($imports, UseStatement::class);

		$this->imports = [];

		foreach ($imports as $import) {
			$this->addImport($import);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\ImperativeCode\UseStatement[] */
	public function getImports(): array
	{
		return $this->imports;
	}

	public function addImportable(Importable $importable): self
	{
		$this->imports[$importable->getFullName()] = $importable->getImportStatement();

		return $this;
	}

	protected function isImportable($value): bool
	{
		return $value instanceof Importable;
	}

	/**
	 * @param \Shomisha\Stubless\Contracts\DelegatesImports[] $delegates
	 * @return \Shomisha\Stubless\ImperativeCode\UseStatement[]
	 */
	protected function gatherImportsFromDelegates(array $delegates): array
	{
		$imports = [];

		foreach ($delegates as $delegate) {
			$imports = array_merge($imports, $delegate->getDelegatedImports());
		}

		return $imports;
	}
}