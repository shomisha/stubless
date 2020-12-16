<?php

namespace Shomisha\Stubless\Templates;

use Shomisha\Stubless\Contracts\DelegatesImports as DelegatesImportsContract;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Templates\Concerns\CanBeStatic;
use Shomisha\Stubless\Templates\Concerns\DelegatesImports as DelegatesImportsConcern;
use Shomisha\Stubless\Templates\Concerns\HasAccessModifier;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Concerns\HasName;
use Shomisha\Stubless\Templates\Concerns\HasValue;

class ClassProperty extends Template implements DelegatesImportsContract
{
	use HasAccessModifier, CanBeStatic, HasName, HasValue, HasImports, DelegatesImportsConcern;

	private ?string $type;

	public function __construct(string $name, string $type = null, string $value = null, ClassAccess $access = null)
	{
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->access = $access ?? ClassAccess::PUBLIC();
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
		$this->type = (string) $type;

		if ($this->isImportable($type)) {
			$this->addImportable($type);
		}

		return $this;
	}

	public function getPrintableNodes(): array
	{
		$property = $this->getFactory()->property($this->name);

		$this->setAccessToBuilder($property);
		$this->makeBuilderStatic($property);

		if ($this->type !== null) {
			$property->setType($this->type);
		}

		if ($this->value) {
			$property->setDefault(
				$this->getFactory()->val($this->value)
			);
		}

		return [$this->convertBuilderToNode($property)];
	}

	public function getImportSubDelegates(): array
	{
		return [];
	}
}