<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Values\AssignableValue;

abstract class InvokeBlock extends AssignableValue implements Arrayable
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

	public function getImportSubDelegates(): array
	{
		$subDelegates = $this->extractImportDelegatesFromArray($this->arguments);

		if ($this->hasChainedMethod()) {
			$subDelegates[] = $this->chainedMethod;
		}

		return $subDelegates;
	}

	public function getPrintableArrayExpr(): Expr
	{
		return $this->getPrintableNodes()[0];
	}
}