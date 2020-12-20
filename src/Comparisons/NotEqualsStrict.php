<?php

namespace Shomisha\Stubless\Comparisons;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;

class NotEqualsStrict extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new NotIdentical($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}