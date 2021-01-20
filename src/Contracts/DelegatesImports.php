<?php

namespace Shomisha\Stubless\Contracts;

interface DelegatesImports
{
	/** @return \Shomisha\Stubless\ImperativeCode\UseStatement[] */
	public function getDelegatedImports(): array;
}