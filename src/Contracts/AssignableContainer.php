<?php

namespace Shomisha\Stubless\Contracts;

use PhpParser\Node\Expr;

interface AssignableContainer
{
	public function getAssignableContainerExpression(): Expr;
}