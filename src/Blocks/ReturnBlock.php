<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\Node\Stmt\Return_;
use Shomisha\Stubless\Values\AssignableValue;

class ReturnBlock extends Block
{
	private AssignableValue $value;

	public function __construct(AssignableValue $returnValue)
	{
		$this->value = $returnValue;
	}

	public function getPrintableNodes(): array
	{
		return [new Return_($this->value->getAssignableValueExpression())];
	}
}