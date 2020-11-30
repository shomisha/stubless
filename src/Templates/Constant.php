<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\BuilderHelpers;
use PhpParser\Node\Const_ as Const_Node;
use PhpParser\Node\Stmt\Const_ as Const_Stmnt;
use Shomisha\Stubless\Templates\Concerns\HasName;
use Shomisha\Stubless\Templates\Concerns\HasValue;

class Constant extends Template
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