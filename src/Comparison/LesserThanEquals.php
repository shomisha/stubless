<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;

class LesserThanEquals extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new SmallerOrEqual($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}