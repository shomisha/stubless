<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\DeclarativeCode\ClassProperty;

/** @mixin \Shomisha\Stubless\Abstractions\DeclarativeCode */
trait HasProperties
{
	/** @var \Shomisha\Stubless\DeclarativeCode\ClassProperty[] */
	protected array $properties = [];

	/** @param \Shomisha\Stubless\DeclarativeCode\ClassProperty[] $properties */
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

	/** @param \Shomisha\Stubless\DeclarativeCode\ClassProperty[] $properties */
	public function withProperties(array $properties): self
	{
		$this->validateArrayElements($properties, ClassProperty::class);

		$this->properties = [];

		foreach ($properties as $property) {
			$this->addProperty($property);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\DeclarativeCode\ClassProperty[] */
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