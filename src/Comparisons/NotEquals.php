<?php

namespace Shomisha\Stubless\Comparisons;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\NotEqual;

class NotEquals extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new NotEqual($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}