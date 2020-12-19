<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class AndComparison extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new BooleanAnd($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}