<?php

namespace Shomisha\Stubless\Values;

class ArrayValue extends Value
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
}