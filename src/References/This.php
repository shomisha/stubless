<?php

namespace Shomisha\Stubless\References;

class This extends Variable
{
	public function __construct()
	{
		parent::__construct('this');
	}
}