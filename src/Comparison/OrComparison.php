<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class OrComparison extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new BooleanOr($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression()),
		];
	}
}