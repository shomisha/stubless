<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;

class GreaterThanEquals extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new GreaterOrEqual($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}