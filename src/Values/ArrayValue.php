<?php

namespace Shomisha\Stubless\Values;

class ArrayValue extends Value
{
	protected array $raw;

	public function __construct(array $raw)
	{
		$this->raw = $raw;
	}

	public function getRaw()
	{
		return $this->raw;
	}

	protected function getPrintableRaw()
	{
		return array_map(function ($value) {
			if ($value instanceof AssignableValue) {
				return $value->getPrintableNodes()[0];
			}

			return $value;
		}, $this->raw);
	}
}