<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Smaller;

class LesserThan extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new Smaller($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}