<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;

class LesserThanEquals extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new SmallerOrEqual($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}