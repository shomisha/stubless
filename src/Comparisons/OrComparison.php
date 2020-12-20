<?php

namespace Shomisha\Stubless\Comparisons;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class OrComparison extends Comparison
{
	public function getComparableNode(): Node
	{
		return new BooleanOr($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}