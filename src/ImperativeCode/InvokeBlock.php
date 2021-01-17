<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Contracts\ObjectContainer;
use Shomisha\Stubless\Values\AssignableValue;

abstract class InvokeBlock extends AssignableValue implements Arrayable, ObjectContainer
{
	protected string $name;

	protected array $arguments;

	protected ?ChainedMethodBlock $chainedMethod = null;

	public function __construct(string $name, array $arguments = [])
	{
		$this->name = $name;
		$this->arguments = array_map(function ($value) {
			return AssignableValue::normalize($value);
		}, $arguments);
	}

	public function chain(string $name = null, array $arguments = [])
	{
		if ($name !== null) {
			$chainedMethod = new ChainedMethodBlock($this, $name, $arguments);
			return $this->setChain($chainedMethod);
		}

		return $this->getChainedMethod();
	}

	public function setChain(?ChainedMethodBlock $block): ?ChainedMethodBlock
	{
		$this->chainedMethod = $block;

		return $block;
	}

	/** @return \Shomisha\Stubless\ImperativeCode\ChainedMethodBlock[] */
	public function getChainedMethod(): ?ChainedMethodBlock
	{
		return $this->chainedMethod;
	}

	public function hasChainedMethod(): bool
	{
		return $this->chainedMethod !== null;
	}

	final public function getPrintableNodes(): array
	{
		if ($this->hasChainedMethod()) {
			return $this->getChainedMethod()->getPrintableNodes();
		}

		return $this->getInvokablePrintableNodes();
	}

	/** @return \PhpParser\Node[] */
	protected abstract function getInvokablePrintableNodes(): array;

	/** @return \PhpParser\Node\Arg[] */
	protected function normalizedArguments(): array
	{
		return array_map(function (AssignableValue $argument) {
			return new Arg($argument->getAssignableValueExpression());
		}, $this->arguments);
	}

	public function getDelegatedImports(): array
	{
		$imports = $this->getImports();

		if ($this->hasChainedMethod()) {
			$imports = array_merge($imports, $this->chainedMethod->getChainedImports());
		}

		foreach ($this->getImportSubDelegates() as $subDelegate) {
			$imports = array_merge($imports, $subDelegate->getDelegatedImports());
		}

		return $imports;
	}

	public function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray($this->arguments);
	}

	public function getPrintableArrayExpr(): Expr
	{
		return $this->getPrintableNodes()[0];
	}

	public function getObjectContainerExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}
}