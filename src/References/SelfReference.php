<?php

namespace Shomisha\Stubless\References;

class SelfReference extends ClassReference
{
	public function __construct()
	{
		parent::__construct('self');
	}
}