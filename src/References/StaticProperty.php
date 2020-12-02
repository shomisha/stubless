<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;
use Shomisha\Stubless\Contracts\DelegatesImports;

class StaticProperty extends Variable implements DelegatesImports
{
	private string $class;

	public function __construct($class, string $name)
	{
		parent::__construct($name);

		$this->class = $class;

		if ($this->isImportable($class)) {
			$this->addImportable($class);
		}
	}

	public function getPrintableNodes(): array
	{
		return [new StaticPropertyFetch(new Name($this->class), $this->name)];
	}

	public function getDelegatedImports(): array
	{
		return $this->imports;
	}
}