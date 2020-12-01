<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Templates\Concerns\HasName;

class Variable extends Reference implements AssignableContainer
{
	use HasName;

	public static function fromArgument(Argument $argument): self
	{
		return self::name($argument->getName());
	}

	public function getPrintableNodes(): array
	{
		return [new \PhpParser\Node\Expr\Variable($this->name)];
	}

	public function getAssignableContainerExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}

	public function getAssignableValueExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}
}