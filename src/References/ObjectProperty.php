<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr\PropertyFetch;
use Shomisha\Stubless\Contracts\ObjectContainer;

class ObjectProperty extends Variable
{
	private ObjectContainer $object;

	public function __construct(ObjectContainer $object, string $name)
	{
		$this->variable = $object;
		$this->name = $name;
	}

	public function getPrintableNodes(): array
	{
		return [
			new PropertyFetch($this->variable->getPrintableNodes()[0], $this->name),
		];
	}
}