<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Identical;

class EqualsStrict extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new Identical($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}