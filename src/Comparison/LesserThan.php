<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\Smaller;

class LesserThan extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new Smaller($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}