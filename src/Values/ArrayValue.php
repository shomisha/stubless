<?php

namespace Shomisha\Stubless\Values;

use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\Arrayable;

class ArrayValue extends Value implements Arrayable
{
	protected array $elements;

	public function __construct(array $raw)
	{
		$this->elements = array_map(function ($element) {
			return Value::normalize($element);
		}, $raw);
	}

	public function getRaw()
	{
		return array_map(function (AssignableValue $value) {
			if ($value instanceof Value) {
				return $value->getRaw();
			}

			return $value;
		}, $this->elements);
	}

	protected function getPrintableRaw()
	{
		return array_map(function (AssignableValue $value) {
			return $value->getPrintableNodes()[0];
		}, $this->elements);
	}

	public function getPrintableArrayExpr(): Expr
	{
		return $this->getPrintableNodes()[0];
	}

	protected function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray($this->elements);
	}
}