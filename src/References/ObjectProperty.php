<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr\PropertyFetch;

class ObjectProperty extends Variable
{
	private Variable $variable;

	public function __construct(Variable $variable, string $name)
	{
		$this->variable = $variable;
		$this->name = $name;
	}

	public function getPrintableNodes(): array
	{
		return [
			new PropertyFetch($this->variable->getPrintableNodes()[0], $this->name),
		];
	}
}