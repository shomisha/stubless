<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\DeclarativeCode\Argument;
use Shomisha\Stubless\Concerns\HasName;

class Variable extends Reference implements AssignableContainer, Arrayable
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

	public function getPrintableArrayExpr(): Expr
	{
		return $this->getPrintableNodes()[0];
	}
}