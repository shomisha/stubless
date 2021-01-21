<?php

namespace Shomisha\Stubless\DeclarativeCode;

use Shomisha\Stubless\Abstractions\DeclarativeCode;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Concerns\HasDocBlock;
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
	use
		CanBeFinal,
		CanBeAbstract,
		HasAccessModifier,
		CanBeStatic,
		HasName,
		HasArguments,
		HasImports,
		DelegatesImportsConcern,
		HasDocBlock;

	private ?string $returnType;

	private ImperativeCode $body;

	public function __construct(string $name, array $arguments = [], ClassAccess $access = null, string $returnType = null)
	{
		$this->name = $name;
		$this->arguments = $arguments;
		$this->access = $access ?? ClassAccess::PUBLIC();
		$this->returnType = $returnType;
	}

	public function body(ImperativeCode $body = null)
	{
		if ($body !== null) {
			return $this->setBody($body);
		}

		return $this->getBody();
	}

	public function setBody(ImperativeCode $body): self
	{
		$this->body = $body;

		return $this;
	}

	public function getBody(): ?ImperativeCode
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

	public function withDefaultDocBlock(): self
	{
		$docBlock = '';

		/** @var \Shomisha\Stubless\DeclarativeCode\Argument $argument */
		foreach ($this->arguments as $argument) {
			$argumentDoc = "@param";

			if ($type = $argument->getType()) {
				$argumentDoc .= " {$type}";
			}

			$argumentDoc .= " \${$argument->getName()}";

			$docBlock .= "{$argumentDoc}\n";
		}

		if ($returnType = $this->returnType) {
			$docBlock .= "@return {$returnType}";
		}

		return $this->withDocBlock($docBlock);
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

		$this->setDocBlockCommentOnBuilder($method);

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