<?php

namespace Shomisha\Stubless\Contracts;

use PhpParser\Node;

interface Template
{
	public function save(string $path): bool;

	public function print(): string;
}