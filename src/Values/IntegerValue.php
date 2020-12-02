<?php

namespace Shomisha\Stubless\Values;

class IntegerValue extends Value
{
	protected int $raw;

	public function __construct(int $raw)
	{
		$this->raw = $raw;
	}

	public function getRaw()
	{
		return $this->raw;
	}
}