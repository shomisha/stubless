<?php

namespace Shomisha\Stubless\Contracts;

interface Formatter
{
	public function format(string $code): string;
}