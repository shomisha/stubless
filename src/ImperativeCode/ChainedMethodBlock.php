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
}