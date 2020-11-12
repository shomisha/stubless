<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\Templates\ClassProperty;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasProperties
{
	/** @var \Shomisha\Stubless\Templates\ClassProperty[] */
	protected array $properties = [];

	public function addProperty(ClassProperty $property): self
	{
		$this->properties[$property->getName()] = $property;

		return $this;
	}

	public function removeProperty(string $name): self
	{
		unset($this->properties[$name]);

		return $this;
	}

	/** @param \Shomisha\Stubless\Templates\ClassProperty[] $properties */
	public function withProperties(array $properties): self
	{
		$this->validateArrayElements($properties, ClassProperty::class);

		$this->properties = $properties;

		return $this;
	}

	protected function addPropertiesToDeclaration(Declaration $declaration): void
	{
		foreach ($this->properties as $property) {
			$declaration->addStmt($property->constructNode());
		}
	}
}