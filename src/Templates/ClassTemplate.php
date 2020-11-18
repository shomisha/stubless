<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Templates\Concerns\CanBeAbstract;
use Shomisha\Stubless\Templates\Concerns\CanBeFinal;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Concerns\HasMethods;
use Shomisha\Stubless\Templates\Concerns\HasName;
use Shomisha\Stubless\Templates\Concerns\HasNamespace;
use Shomisha\Stubless\Templates\Concerns\HasProperties;
use Shomisha\Stubless\Utilities\Importable;

class ClassTemplate extends Template
{
	use HasNamespace, HasImports, CanBeAbstract, CanBeFinal, HasName, HasProperties, HasMethods;

	private ?string $extends = null;

	private array $interfaces = [];

	private array $traits = [];

	private array $constants = [];

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

	public function implements(array $interfaces)
	{
		if (!empty($interfaces)) {
			return $this->setInterfaces($interfaces);
		}

		return $this->getInterfaces();
	}

	public function getInterfaces(): array
	{
		return $this->interfaces;
	}

	public function setInterfaces(array $interfaces): self
	{
		$actualInterfaces = [];

		foreach ($interfaces as $interface) {
			if ($interface instanceof Importable) {
				$actualInterfaces[] = $interface->getShortName();
				$this->addImportable($interface);
			} elseif (is_string($interface)) {
				$actualInterfaces[] = $interface;
			}
		}

		$this->interfaces = $actualInterfaces;
		return $this;
	}

	public function uses(array $traits = null)
	{
		if ($traits === null) {
			return $this->getTraits();
		}

		return $this->setTraits($traits);
	}

	public function getTraits(): array
	{
		return $this->traits;
	}

	public function setTraits(array $traits): self
	{
		$actualTraits = [];

		foreach ($traits as $trait) {
			if ($trait instanceof Importable) {
				$actualTraits[] = $trait->getShortName();
				$this->addImportable($trait);
			} elseif (is_string($trait)) {
				$actualTraits[] = $trait;
			}
		}

		$this->traits = $actualTraits;

		return $this;
	}

	public function constants(array $constants = null)
	{
		if ($constants !== null) {
			return $this->setConstants($constants);
		}

		return $this->getConstants();
	}

	public function getConstants(): array
	{
		return $this->constants;
	}

	/** @param \Shomisha\Stubless\Templates\ClassConstant[] $constants */
	public function setConstants(array $constants): self
	{
		$this->validateArrayElements($constants, ClassConstant::class);

		$namedConstants = [];
		foreach ($constants as $constant) {
			$namedConstants[$constant->getName()] = $constant;
		}

		$this->constants = $namedConstants;

		return $this;
	}

	public function withConstant(ClassConstant $constant): self
	{
		$this->constants[$constant->getName()] = $constant;

		return $this;
	}

	public function withoutConstant(string $constantName): self
	{
		unset($this->constants[$constantName]);

		return $this;
	}

	public function constructNode(): Node
	{
		$class = $this->getFactory()->class($this->name);

		if (!empty($this->interfaces)) {
			$class->implement(...$this->interfaces);
		}

		$this->makeBuilderFinal($class);
		$this->makeBuilderAbstract($class);

		if ($this->extends !== null) {
			$class->extend($this->extends);
		}

		if (!empty($this->traits)) {
			$class->addStmt($this->getFactory()->useTrait(...$this->traits));
		}

		foreach ($this->constants as $constant) {
			$class->addStmt($constant->constructNode());
		}

		$this->addPropertiesToDeclaration($class);
		$this->addMethodsToDeclaration($class);

		if ($this->hasNamespace()) {
			$namespace = $this->getNamespaceBuilder();

			foreach ($this->gatherAllImports() as $import) {
				$namespace->addStmt($import->constructNode());
			}


			$namespace->addStmt($class);

			return $this->convertBuilderToNode($namespace);
		}

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