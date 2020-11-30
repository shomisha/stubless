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
		$this->extends = (string) $extends;

		if ($this->isImportable($extends)) {
			$this->addImportable($extends);
		}

		return $this;
	}

	public function implements(array $interfaces = null)
	{
		if ($interfaces !== null) {
			return $this->withInterfaces($interfaces);
		}

		return $this->getInterfaces();
	}

	/** @param string|\Shomisha\Stubless\Utilities\Importable $interface */
	public function addInterface($interface): self
	{
		$actualInterface = (string) $interface;

		$this->interfaces[$actualInterface] = $actualInterface;

		if ($this->isImportable($interface)) {
			$this->addImportable($interface);
		}

		return $this;
	}

	public function removeInterface(string $interfaceName): self
	{
		unset($this->interfaces[$interfaceName]);

		return $this;
	}

	public function withInterfaces(array $interfaces): self
	{
		$this->interfaces = [];

		foreach ($interfaces as $interface) {
			$this->addInterface($interface);
		}

		return $this;
	}

	/** @return string[] */
	public function getInterfaces(): array
	{
		return $this->interfaces;
	}

	public function uses(array $traits = null)
	{
		if ($traits === null) {
			return $this->getTraits();
		}

		return $this->withTraits($traits);
	}

	/** @param string|\Shomisha\Stubless\Utilities\Importable $trait */
	public function addTrait($trait): self
	{
		$actualTrait = (string) $trait;

		$this->traits[$actualTrait] = $actualTrait;

		if ($this->isImportable($trait)) {
			$this->addImportable($trait);
		}

		return $this;
	}

	public function removeTrait(string $trait): self
	{
		unset($this->traits[$trait]);

		return $this;
	}

	public function withTraits(array $traits): self
	{
		$this->traits = [];

		foreach ($traits as $trait) {
			$this->addTrait($trait);
		}

		return $this;
	}

	public function getTraits(): array
	{
		return $this->traits;
	}

	public function constants(array $constants = null)
	{
		if ($constants !== null) {
			return $this->withConstants($constants);
		}

		return $this->getConstants();
	}

	public function addConstant(ClassConstant $constant): self
	{
		$this->constants[$constant->getName()] = $constant;

		return $this;
	}

	public function removeConstant(string $constantName): self
	{
		unset($this->constants[$constantName]);

		return $this;
	}

	/** @param \Shomisha\Stubless\Templates\ClassConstant[] $constants */
	public function withConstants(array $constants): self
	{
		$this->validateArrayElements($constants, ClassConstant::class);

		$this->constants = [];

		foreach ($constants as $constant) {
			$this->addConstant($constant);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\Templates\ClassConstant[] */
	public function getConstants(): array
	{
		return $this->constants;
	}

	public function getPrintableNodes(): array
	{
		return array_values(
			array_filter([
				$this->constructNamespaceNode(),
				...$this->constructImportNodes(),
				$this->constructClassNode(),
			])
		);
	}

	protected function constructClassNode(): Node
	{
		$class = $this->getFactory()->class($this->name);

		if (!empty($this->interfaces)) {
			$class->implement(...array_values($this->interfaces));
		}

		$this->makeBuilderFinal($class);
		$this->makeBuilderAbstract($class);

		if ($this->extends !== null) {
			$class->extend($this->extends);
		}

		if (!empty($this->traits)) {
			$class->addStmt($this->getFactory()->useTrait(...array_values($this->traits)));
		}

		foreach ($this->constants as $constant) {
			$class->addStmt($constant->getPrintableNodes()[0]);
		}

		$this->addPropertiesToDeclaration($class);
		$this->addMethodsToDeclaration($class);

		return $this->convertBuilderToNode($class);
	}

	protected function constructNamespaceNode(): ?Node
	{
		if ($this->hasNamespace()) {
			return $this->convertBuilderToNode(
				$this->getFactory()->namespace($this->namespace)
			);
		}

		return null;
	}

	/** @return \PhpParser\Node\Stmt\Use_[] */
	protected function constructImportNodes(): array
	{
		$imports = [];

		foreach ($this->gatherAllImports() as $import) {
			$imports[] = $import->getPrintableNodes()[0];
		}

		return $imports;
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