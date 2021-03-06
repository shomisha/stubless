<?php

namespace Shomisha\Stubless\Comparisons;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Equal;

class Equals extends Comparison
{
	protected function getComparableNode(): Node
	{
		return new Equal($this->first->getAssignableValueExpression(), $this->second->getAssignableValueExpression());
	}
}