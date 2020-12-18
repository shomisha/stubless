<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use Shomisha\Stubless\Concerns\HasName;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\AssignableValue;

class ClassReference extends Reference
{
	use HasName;

	public function __construct($class)
	{
		$this->name = $class;

		if ($this->isImportable($class)) {
			$this->addImportable($class);
		}
	}

	public static function normalize($value): ClassReference
	{
		if ($value instanceof ClassReference) {
			return $value;
		}

		if (is_string($value)) {
			return new self($value);
		}

		if ($value instanceof Importable) {
			return new self($value);
		}

		throw new \InvalidArgumentException("Value cannot be safely normalized to a ClassReference instance.");
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