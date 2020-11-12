<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Builder\Property;
use PhpParser\Node;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Templates\Concerns\HasAccessModifier;

class ClassProperty extends Template
{
	use HasAccessModifier;

	private ?string $type;

	private string $name;

	private ?string $value;

	public function __construct(string $name, string $type, string $value = null, ClassAccess $access = null)
	{
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->access = $access ?? ClassAccess::PUBLIC();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function constructNode(): Node
	{
		$property = $this->getFactory()->property($this->name);

		$this->setAccessToBuilder($property);

		if ($this->type !== null) {
			$property->setType($this->type);
		}

		if ($this->value) {
			$property->setDefault($this->value);
		}

		return $this->convertBuilderToNode($property);
	}
}