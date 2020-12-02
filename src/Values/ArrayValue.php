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
}