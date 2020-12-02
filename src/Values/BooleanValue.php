<?php

namespace Shomisha\Stubless\Values;

class BooleanValue extends Value
{
	protected bool $raw;

	public function __construct(bool $raw)
	{
		$this->raw = $raw;
	}

	public function getRaw()
	{
		return $this->raw;
	}
}