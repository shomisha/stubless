<?php

namespace Shomisha\Stubless\DeclarativeCode;

use PhpParser\BuilderHelpers;
use PhpParser\Node\Const_ as Const_Node;
use PhpParser\Node\Stmt\Const_ as Const_Stmnt;
use Shomisha\Stubless\Abstractions\DeclarativeCode;
use Shomisha\Stubless\Concerns\HasName;
use Shomisha\Stubless\Concerns\HasValue;

class Constant extends DeclarativeCode
{
	use HasName, HasValue;

	public function getPrintableNodes(): array
	{
		$value = (isset($this->value))
			? $this->value
			: null;

		return [new Const_Stmnt([
			new Const_Node($this->name, BuilderHelpers::normalizeValue($value))
		])];
	}
}