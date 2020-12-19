<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class AndComparison extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new BooleanAnd($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression()),
		];
	}
}