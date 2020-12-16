<?php

namespace Shomisha\Stubless\Templates;

use Shomisha\Stubless\Contracts\DelegatesImports as DelegatesImportsContract;
use Shomisha\Stubless\Templates\Concerns\DelegatesImports as DelegatesImportsConcern;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Concerns\HasName;
use Shomisha\Stubless\Templates\Concerns\HasValue;

class Argument extends Template implements DelegatesImportsContract
{
	use HasName, HasValue, HasImports, DelegatesImportsConcern;

	private ?string $type;

	public function __construct(string $name, string $type = null)
	{
		$this->name = $name;
		$this->type = $type;
	}

	public function type($type = null)
	{
		if ($type === null) {
			return $this->getType();
		}

		return $this->setType($type);
	}

	public function getType(): string
	{
		return $this->type;
	}

	/** @param string|\Shomisha\Stubless\Utilities\Importable $type */
	public function setType($type): self
	{
		if ($this->isImportable($type)) {
			$this->type = $type->getShortName();
			$this->addImportable($type);
		} else {
			$this->type = $type;
		}

		return $this;
	}

	/** @return \PhpParser\Node\Param */
	public function getPrintableNodes(): array
	{
		$argument = $this->getFactory()->param($this->name);

		if ($this->type !== null) {
			$argument->setType($this->type);
		}

		if (isset($this->value)) {
			$argument->setDefault($this->value);
		}

		return [$this->convertBuilderToNode($argument)];
	}

	public function getImportSubDelegates(): array
	{
		return [];
	}
}