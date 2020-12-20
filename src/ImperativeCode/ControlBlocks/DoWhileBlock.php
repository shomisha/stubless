<?php

namespace Shomisha\Stubless\ImperativeCode\ControlBlocks;

use PhpParser\Node\Stmt\Do_;

class DoWhileBlock extends WhileBlock
{
	public function getPrintableNodes(): array
	{
		return [
			new Do_(
				$this->getPrintableConditionExpression(),
				$this->getPrintableBodyExpressions()
			),
		];
	}
}