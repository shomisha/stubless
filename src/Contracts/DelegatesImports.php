<?php

namespace Shomisha\Stubless\Contracts;

interface DelegatesImports
{
	/** @return \Shomisha\Stubless\DeclarativeCode\UseStatement[] */
	public function getDelegatedImports(): array;
}