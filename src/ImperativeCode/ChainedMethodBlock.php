<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Expr\MethodCall;

class ChainedMethodBlock extends InvokeBlock
{
	private InvokeBlock $parent;

	public function __construct(InvokeBlock $parent, string $name, array $arguments = [])
	{
		parent::__construct($name, $arguments);
		$this->parent = $parent;
	}

	public function print(): string
	{
		return $this->parent->print();
	}

	protected function getInvokablePrintableNodes(): array
	{
		return [
			new MethodCall(
				$this->parent->getInvokablePrintableNodes()[0],
				$this->name,
				$this->normalizedArguments()
			)
		];
	}

	public function getDelegatedImports(): array
	{
		return $this->parent->getDelegatedImports();
	}

	protected function getChainedImports(): array
	{
		$imports = $this->getImports();

		if ($this->hasChainedMethod()) {
			$imports = array_merge($this->chainedMethod->getChainedImports());
		}

		foreach ($this->extractImportDelegatesFromArray($this->arguments) as $argument) {
			$imports = array_merge($imports, $argument->getDelegatedImports());
		}

		return $imports;
	}
}