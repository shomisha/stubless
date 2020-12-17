<?php

namespace Shomisha\Stubless\Contracts;

interface Code
{
	public function save(string $path): bool;

	public function print(): string;
}