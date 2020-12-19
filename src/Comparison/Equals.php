<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\Equal;

class Equals extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new Equal($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression()),
		];
	}
}