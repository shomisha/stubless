<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\NotEqual;

class NotEquals extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new NotEqual($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}