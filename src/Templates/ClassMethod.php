<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Templates\Concerns\CanBeAbstract;
use Shomisha\Stubless\Templates\Concerns\CanBeFinal;
use Shomisha\Stubless\Templates\Concerns\HasAccessModifier;

class ClassMethod extends Template
{
	use CanBeFinal, CanBeAbstract, HasAccessModifier;

	private ClassAccess $access;

	private string $name;

	/** @var \Shomisha\Stubless\Templates\Argument[] */
	private array $arguments = [];

	private ?string $returnType;

	public function __construct(string $name, array $arguments = [], ClassAccess $access = null, string $returnType = null)
	{
		$this->name = $name;
		$this->arguments = $arguments;
		$this->access = $access ?? ClassAccess::PUBLIC();
		$this->returnType = $returnType;
	}

	public function getName():string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function addArgument(Argument $argument): self
	{
		$this->arguments[] = $argument;

		return $this;
	}

	/** @param \Shomisha\Stubless\Templates\Argument[] */
	public function withArguments(array $arguments): self
	{
		$this->validateArrayElements($arguments, Argument::class);

		$this->arguments = $arguments;

		return $this;
	}

	/** @return \PhpParser\Node\Stmt\ClassMethod */
	public function constructNode(): Node
	{
		$method = $this->getFactory()->method($this->name);

		foreach ($this->arguments as $argument) {
			$method->addParam($argument->constructNode());
		}

		$this->makeBuilderAbstract($method);
		$this->makeBuilderFinal($method);

		$this->setAccessToBuilder($method);

		return $this->convertBuilderToNode($method);
	}
}