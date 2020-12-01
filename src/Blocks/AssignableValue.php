<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\Node\Expr;

abstract class AssignableValue extends Block
{
	public function getAssignableValueExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}
}