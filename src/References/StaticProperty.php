<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;

class StaticProperty extends Variable
{
	private ClassReference $class;

	public function __construct(ClassReference $class, string $name)
	{
		parent::__construct($name);

		$this->class = $class;
	}

	public function getPrintableNodes(): array
	{
		return [new StaticPropertyFetch(new Name($this->class->getName()), $this->name)];
	}

	protected function getImportSubDelegates(): array
	{
		return [
			$this->class
		];
	}
}