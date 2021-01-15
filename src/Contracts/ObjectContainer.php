<?php

namespace Shomisha\Stubless\Contracts;

use PhpParser\Node\Expr;

interface ObjectContainer
{
	public function getObjectContainerExpression(): Expr;
}