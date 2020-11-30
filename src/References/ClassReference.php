<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\Templates\Concerns\HasImports;

class ClassReference extends Reference implements DelegatesImports
{
	use HasImports;

	private string $class;

	public function __construct($class)
	{
		$this->class = $class;

		if ($this->isImportable($class)) {
			$this->addImportable($class);
		}
	}

	public function getPrintableNodes(): array
	{
		return [
			new ClassConstFetch(new Name($this->class), 'class'),
		];
	}

	public function getDelegatedImports(): array
	{
		return $this->imports;
	}
}