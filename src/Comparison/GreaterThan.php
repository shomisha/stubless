<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Greater;

class GreaterThan extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new Greater($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}