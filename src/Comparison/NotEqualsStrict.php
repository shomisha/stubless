<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node\Expr\BinaryOp\NotIdentical;

class NotEqualsStrict extends Comparison
{
	public function getPrintableNodes(): array
	{
		return [
			new NotIdentical($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression())
		];
	}
}