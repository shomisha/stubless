<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;

class InvokeFunctionBlock extends InvokeBlock
{
	public function getPrintableNodes(): array
	{
		return [
			new FuncCall(
				new Name($this->name),
				$this->normalizedArguments()
			)
		];
	}
}