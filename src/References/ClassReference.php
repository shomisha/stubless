<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use Shomisha\Stubless\Contracts\DelegatesImports as DelegatesImportsContract;
use Shomisha\Stubless\Templates\Concerns\HasName;

class ClassReference extends Reference implements DelegatesImportsContract
{
	use HasName;

	public function __construct($class)
	{
		$this->name = $class;

		if ($this->isImportable($class)) {
			$this->addImportable($class);
		}
	}

	public function getPrintableNodes(): array
	{
		return [
			new ClassConstFetch(new Name($this->name), 'class'),
		];
	}

	public function getImportSubDelegates(): array
	{
		return [];
	}

	public function getAssignableValueExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}
}