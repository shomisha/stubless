<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;

class InvokeFunctionBlock extends InvokeBlock
{
	public function getInvokablePrintableNodes(): array
	{
		return [
			new FuncCall(
				new Name($this->name),
				$this->normalizedArguments()
			)
		];
	}
}