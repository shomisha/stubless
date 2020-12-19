<?php

namespace Shomisha\Stubless\Comparison;

use PhpParser\Node;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\Values\AssignableValue;

abstract class Comparison extends AssignableValue implements DelegatesImports
{
	protected AssignableValue $first;

	protected AssignableValue $second;

	private bool $negated = false;

	public function __construct(AssignableValue $first, AssignableValue $second)
	{
		$this->first = $first;
		$this->second = $second;
	}

	public function or($second): OrComparison
	{
		return new OrComparison($this, $second);
	}

	public function and($second): AndComparison
	{
		return new AndComparison($this, AssignableValue::normalize($second));
	}

	public function negate(bool $negate = true): self
	{
		$this->negated = $negate;

		return $this;
	}

	final public function getPrintableNodes(): array
	{
		$comparableNode = $this->getComparableNode();

		if ($this->negated) {
			$comparableNode = new Node\Expr\BooleanNot($comparableNode);
		}

		return [
			$comparableNode
		];
	}

	abstract protected function getComparableNode(): Node;

	protected function getImportSubDelegates(): array
	{
		return [
			$this->first,
			$this->second
		];
	}

	public static function equals($first, $second): Equals
	{
		return new Equals(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function equalsStrict($first, $second): EqualsStrict
	{
		return new EqualsStrict(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function notEquals($first, $second): NotEquals
	{
		return new NotEquals(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function notEqualsStrict($first, $second): NotEqualsStrict
	{
		return new NotEqualsStrict(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function greaterThan($first, $second): GreaterThan
	{
		return new GreaterThan(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function greaterThanEquals($first, $second): GreaterThanEquals
	{
		return new GreaterThanEquals(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function lesserThan($first, $second): LesserThan
	{
		return new LesserThan(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}

	public static function lesserThanEquals($first, $second): LesserThanEquals
	{
		return new LesserThanEquals(AssignableValue::normalize($first), AssignableValue::normalize($second));
	}
}