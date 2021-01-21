<?php

namespace Shomisha\Stubless\Comparisons;

use PhpParser\Node;
use Shomisha\Stubless\Values\AssignableValue;
use Shomisha\Stubless\Values\Value;

class Not extends Comparison
{
	public function __construct(AssignableValue $first)
	{
		$this->first = $first;
		$this->second = Value::null();
	}

	protected function getComparableNode(): Node
	{
		return new Node\Expr\BooleanNot($this->first->getAssignableValueExpression());
	}
}