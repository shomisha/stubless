<?php

namespace Shomisha\Stubless\Values;

use PhpParser\Node\Expr;
use Shomisha\Stubless\Abstractions\ImperativeCode;

abstract class Value extends AssignableValue
{
	public static function string(string $raw): StringValue
	{
		return new StringValue($raw);
	}

	public static function integer(int $raw): IntegerValue
	{
		return new IntegerValue($raw);
	}

	public static function float(float $raw): FloatValue
	{
		return new FloatValue($raw);
	}

	public static function array(array $raw): ArrayValue
	{
		return new ArrayValue($raw);
	}

	public static function associativeArray(array $keys, array $values): AssociativeArrayValue
	{
		return new AssociativeArrayValue($keys, $values);
	}

	public static function boolean(bool $raw): BooleanValue
	{
		return new BooleanValue($raw);
	}

	public static function closure(array $arguments, ?ImperativeCode $body = null): Closure
	{
		return new Closure($arguments, $body);
	}

	public static function null(): NullValue
	{
		return new NullValue();
	}

	abstract public function getRaw();

	protected function getPrintableRaw()
	{
		return $this->getRaw();
	}

	public function getAssignableValueExpression(): Expr
	{
		return $this->getFactory()->val($this->getPrintableRaw());
	}

	public function getPrintableNodes(): array
	{
		return [
			$this->getAssignableValueExpression()
		];
	}
}