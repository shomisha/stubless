<?php

namespace Shomisha\Stubless\Values;

class StringValue extends Value
{
	protected string $raw;

	public function __construct(string $raw)
	{
		$this->raw = $raw;
	}

	public function getRaw()
	{
		return $this->raw;
	}
}