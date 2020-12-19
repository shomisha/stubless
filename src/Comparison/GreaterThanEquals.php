<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;

class GreaterThanEquals extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new GreaterOrEqual($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}