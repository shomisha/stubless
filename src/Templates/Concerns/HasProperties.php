<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\Templates\ClassProperty;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasProperties
{
	/** @var \Shomisha\Stubless\Templates\ClassProperty[] */
	protected array $properties = [];

	/** @param \Shomisha\Stubless\Templates\ClassProperty[] $properties */
	public function properties(array $properties = null)
	{
		if ($properties === null) {
			return $this->getProperties();
		}

		return $this->withProperties($properties);
	}

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

		$this->properties = [];

		foreach ($properties as $property) {
			$this->addProperty($property);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\Templates\ClassProperty[] */
	public function getProperties(): array
	{
		return $this->properties;
	}

	protected function addPropertiesToDeclaration(Declaration $declaration): void
	{
		foreach ($this->properties as $property) {
			$declaration->addStmts($property->getPrintableNodes());
		}
	}
}