<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Templates\Concerns\CanBeAbstract;
use Shomisha\Stubless\Templates\Concerns\CanBeFinal;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Concerns\HasMethods;
use Shomisha\Stubless\Templates\Concerns\HasName;
use Shomisha\Stubless\Templates\Concerns\HasProperties;
use Shomisha\Stubless\Utilities\Importable;

class ClassTemplate extends Template
{
	use HasImports, CanBeAbstract, CanBeFinal, HasName, HasProperties, HasMethods;

	private ?string $extends;

	public function __construct(string $name, string $extends = null)
	{
		$this->name = $name;
		$this->extends = $extends;
	}

	public function extends($extends = null)
	{
		if ($extends === null) {
			return $this->getExtends();
		}

		return $this->setExtends($extends);
	}

	public function getExtends(): ?string
	{
		return $this->extends;
	}

	public function setExtends($extends): self
	{
		if ($extends instanceof Importable) {
			$this->extends = $extends->getShortName();
			$this->addImportable($extends);
		} else {
			$this->extends = $extends;
		}

		return $this;
	}

	public function constructNode(): Node
	{
		$class = $this->getFactory()->class($this->name);

		foreach ($this->gatherAllImports() as $import) {
			$class->addStmt($import->constructNode());
		}

		$this->makeBuilderFinal($class);
		$this->makeBuilderAbstract($class);

		if ($this->extends !== null) {
			$class->extend($this->extends);
		}

		$this->addPropertiesToDeclaration($class);
		$this->addMethodsToDeclaration($class);

		return $this->convertBuilderToNode($class);
	}

	/** @return \Shomisha\Stubless\Templates\UseStatement[] */
	protected function gatherAllImports(): array
	{
		return array_merge(
			$this->imports,
			$this->gatherImportsFromDelegates($this->properties),
			$this->gatherImportsFromDelegates($this->methods),
		);
	}
}