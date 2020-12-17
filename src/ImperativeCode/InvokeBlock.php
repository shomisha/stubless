<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\BuilderHelpers;
use PhpParser\Node\Arg;
use Shomisha\Stubless\Values\AssignableValue;

abstract class InvokeBlock extends AssignableValue
{
	protected string $name;

	protected array $arguments;

	protected ?ChainedMethodBlock $chainedMethod = null;

	public function __construct(string $name, array $arguments = [])
	{
		$this->name = $name;
		$this->arguments = $arguments;
	}

	public function chain(string $name = null, array $arguments = [])
	{
		if ($name !== null) {
			$chainedMethod = new ChainedMethodBlock($this, $name, $arguments);
			return $this->setChain($chainedMethod);
		}

		return $this->getChainedMethod();
	}

	public function setChain(?ChainedMethodBlock $block): ChainedMethodBlock
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
		return array_map(function ($argument) {
			if ($argument instanceof AssignableValue) {
				return new Arg($argument->getAssignableValueExpression());
			}

			return BuilderHelpers::normalizeValue($argument);
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
}