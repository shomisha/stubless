<?php

namespace Shomisha\Stubless\DeclarativeCode;

use Shomisha\Stubless\Abstractions\DeclarativeCode;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\Contracts\DelegatesImports as DelegatesImportsContract;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Concerns\CanBeAbstract;
use Shomisha\Stubless\Concerns\CanBeFinal;
use Shomisha\Stubless\Concerns\CanBeStatic;
use Shomisha\Stubless\Concerns\DelegatesImports as DelegatesImportsConcern;
use Shomisha\Stubless\Concerns\HasAccessModifier;
use Shomisha\Stubless\Concerns\HasArguments;
use Shomisha\Stubless\Concerns\HasImports;
use Shomisha\Stubless\Concerns\HasName;

class ClassMethod extends DeclarativeCode implements DelegatesImportsContract
{
	use CanBeFinal, CanBeAbstract, HasAccessModifier, CanBeStatic, HasName, HasArguments, HasImports, DelegatesImportsConcern;

	private ?string $returnType;

	private Block $body;

	public function __construct(string $name, array $arguments = [], ClassAccess $access = null, string $returnType = null)
	{
		$this->name = $name;
		$this->arguments = $arguments;
		$this->access = $access ?? ClassAccess::PUBLIC();
		$this->returnType = $returnType;
	}

	public function body(Block $body = null)
	{
		if ($body !== null) {
			return $this->setBody($body);
		}

		return $this->getBody();
	}

	public function setBody(Block $body): self
	{
		$this->body = $body;

		return $this;
	}

	public function getBody(): ?Block
	{
		return $this->body;
	}

	public function hasBody(): bool
	{
		return isset($this->body);
	}

	public function return($returnType = null)
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

	public function setReturnType($returnType): self
	{
		$this->returnType = (string) $returnType;

		if ($this->isImportable($returnType)) {
			$this->addImportable($returnType);
		}

		return $this;
	}

	/** @return \PhpParser\Node\Stmt\ClassMethod[] */
	public function getPrintableNodes(): array
	{
		$method = $this->getFactory()->method($this->name);

		$this->makeBuilderAbstract($method);
		$this->makeBuilderFinal($method);
		$this->makeBuilderStatic($method);

		$this->setAccessToBuilder($method);

		$this->addArgumentsToFunctionLike($method);

		if ($this->hasBody()) {
			$method->addStmts($this->body->getPrintableNodes());
		}

		if ($this->returnType !== null) {
			$method->setReturnType($this->returnType);
		}

		return [$this->convertBuilderToNode($method)];
	}

	public function getImportSubDelegates(): array
	{
		$subDelegates = [
			...array_values($this->arguments),
		];

		if ($this->hasBody()) {
			$subDelegates[] = $this->body;
		}

		return $subDelegates;
	}
}