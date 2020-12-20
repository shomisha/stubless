<?php

namespace Shomisha\Stubless\Contracts;

use PhpParser\Node\Expr;

interface Arrayable
{
	public function getPrintableArrayExpr(): Expr;
}