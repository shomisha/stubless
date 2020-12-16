<?php

namespace Shomisha\Stubless\Contracts;

interface DelegatesImports
{
	/** @return \Shomisha\Stubless\Templates\UseStatement[] */
	public function getDelegatedImports(): array;

	/** @return \Shomisha\Stubless\Contracts\DelegatesImports[] */
	public function getImportSubDelegates(): array;
}