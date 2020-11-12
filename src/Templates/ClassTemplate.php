<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Templates\Concerns\CanBeAbstract;
use Shomisha\Stubless\Templates\Concerns\CanBeFinal;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Concerns\HasMethods;
use Shomisha\Stubless\Templates\Concerns\HasProperties;

class ClassTemplate extends Template
{
	use HasImports, CanBeAbstract, CanBeFinal, HasProperties, HasMethods;

	private string $name;

	private ?string $extends;

	public function __construct(string $name, string $extends = null)
	{
		$this->name = $name;
		$this->extends = $extends;
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

	public function getExtends(): ?string
	{
		return $this->extends;
	}

	public function setExtends(?string $extends): self
	{
		$this->extends = $extends;

		return $this;
	}

	public function constructNode(): Node
	{
		$class = $this->getFactory()->class($this->name);

		$this->addImportsToDeclaration($class);

		$this->makeBuilderFinal($class);
		$this->makeBuilderAbstract($class);

		if ($this->extends !== null) {
			$class->extend($this->extends);
		}

		$this->addPropertiesToDeclaration($class);
		$this->addMethodsToDeclaration($class);

		return $this->convertBuilderToNode($class);
	}
}