<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Templates\Concerns\CanBeAbstract;
use Shomisha\Stubless\Templates\Concerns\CanBeFinal;
use Shomisha\Stubless\Templates\Concerns\HasAccessModifier;
use Shomisha\Stubless\Templates\Concerns\HasArguments;
use Shomisha\Stubless\Templates\Concerns\HasName;

class ClassMethod extends Template
{
	use CanBeFinal, CanBeAbstract, HasAccessModifier, HasName, HasArguments;

	private ?string $returnType;

	public function __construct(string $name, array $arguments = [], ClassAccess $access = null, string $returnType = null)
	{
		$this->name = $name;
		$this->arguments = $arguments;
		$this->access = $access ?? ClassAccess::PUBLIC();
		$this->returnType = $returnType;
	}

	public function return(string $returnType = null)
	{
		if ($returnType === null) {
			return $this->getReturnType();
		}

		return $this->setReturnType($returnType);
	}

	public function getReturnType(): ?string
	{
		return $this->returnType;
	}

	public function setReturnType(?string $returnType): self
	{
		$this->returnType = $returnType;

		return $this;
	}

	/** @return \PhpParser\Node\Stmt\ClassMethod */
	public function constructNode(): Node
	{
		$method = $this->getFactory()->method($this->name);

		$this->makeBuilderAbstract($method);
		$this->makeBuilderFinal($method);

		$this->setAccessToBuilder($method);

		$this->addArgumentsToFunctionLike($method);

		if ($this->returnType !== null) {
			$method->setReturnType($this->returnType);
		}

		return $this->convertBuilderToNode($method);
	}
}