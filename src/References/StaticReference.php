<?php

namespace Shomisha\Stubless\References;

class StaticReference extends ClassReference
{
	public function __construct()
	{
		parent::__construct('static');
	}
}