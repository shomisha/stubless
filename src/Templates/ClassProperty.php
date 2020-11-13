<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Builder\Property;
use PhpParser\Node;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Templates\Concerns\HasAccessModifier;
use Shomisha\Stubless\Templates\Concerns\HasName;

class ClassProperty extends Template
{
	use HasAccessModifier, HasName;

	private ?string $type;

	/** @var mixed */
	private $value;

	public function __construct(string $name, string $type = null, string $value = null, ClassAccess $access = null)
	{
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->access = $access ?? ClassAccess::PUBLIC();
	}

	public function type(string $type = null)
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

	public function setType(string $type): self
	{
		$this->type = $type;

		return $this;
	}

	public function value($value = null)
	{
		if ($value === null) {
			return $this->getValue();
		}

		return $this->setValue($value);
	}

	public function getValue(): ?string
	{
		return $this->value;
	}

	public function setValue($value): self
	{
		$this->value = $value;

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
			$property->setDefault(
				$this->getFactory()->val($this->value)
			);
		}

		return $this->convertBuilderToNode($property);
	}
}