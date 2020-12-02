<?php

namespace Shomisha\Stubless\Values;

class FloatValue extends Value
{
	protected float $raw;

	public function __construct(float $raw)
	{
		$this->raw = $raw;
	}

	public function getRaw()
	{
		return $this->raw;
	}
}