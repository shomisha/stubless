<?php

namespace Shomisha\Stubless\Contracts;

use PhpParser\Node\Expr;

interface AssignableValue
{
	public function getAssignableValueExpression(): Expr;
}