<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use Shomisha\Stubless\Templates\Concerns\HasName;
use Shomisha\Stubless\Templates\Concerns\HasValue;

class Constant extends Template
{
	use HasName, HasValue;

	public function constructNode(): Node
	{
		$value = (isset($this->value))
			? $this->value
			: null;

		return new Node\Stmt\Const_([
			new Node\Const_($this->name, BuilderHelpers::normalizeValue($value))
		]);
	}
}