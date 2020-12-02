<?php

namespace Shomisha\Stubless\Values;

class NullValue extends Value
{
	public function __construct()
	{
	}

	public function getRaw()
	{
		return null;
	}
}