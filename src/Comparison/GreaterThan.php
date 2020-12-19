<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\Greater;

class GreaterThan extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new Greater($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}