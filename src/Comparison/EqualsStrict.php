<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\Identical;

class EqualsStrict extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new Identical($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}