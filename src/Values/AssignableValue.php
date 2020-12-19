<?php

namespace Shomisha\Stubless\Values;

use PhpParser\Node\Expr;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Utilities\Importable;

abstract class AssignableValue extends ImperativeCode
{
	public function getAssignableValueExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}

	public static function normalize($value): AssignableValue
	{
		if ($value instanceof AssignableValue) {
			return $value;
		}

		if (is_null($value)) {
			return Value::null();
		}

		if (is_string($value)) {
			return Value::string($value);
		}

		if ($value instanceof Importable) {
			return Value::string($value)->addImportable($value);
		}

		if (is_integer($value)) {
			return Value::integer($value);
		}

		if (is_float($value)) {
			return Value::float($value);
		}

		if (is_array($value)) {
			return Value::array($value);
		}

		if (is_bool($value)) {
			return Value::boolean($value);
		}

		throw new \InvalidArgumentException(sprintf(
			"Object of type %s cannot be safely normalized to an instance of %s",
			get_class($value),
			self::class
		));
	}
}