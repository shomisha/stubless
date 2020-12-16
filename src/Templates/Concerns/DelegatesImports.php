<?php

namespace Shomisha\Stubless\Templates\Concerns;

use Shomisha\Stubless\Contracts\DelegatesImports as DelegatesImportsContract;

/** @see \Shomisha\Stubless\Contracts\DelegatesImports */
/** @mixin \Shomisha\Stubless\Templates\Concerns\HasImports */
trait DelegatesImports
{
	/** @return \Shomisha\Stubless\Templates\UseStatement[] */
	public function getDelegatedImports(): array
	{
		$imports = $this->getImports();

		foreach ($this->getImportSubDelegates() as $subDelegate) {
			$imports = array_merge($imports, $subDelegate->getDelegatedImports());
		}

		return $imports;
	}

	public function getImportSubDelegate(): array
	{
		return [];
	}

	protected function extractImportDelegatesFromArray(array $potentialDelegates): array
	{
		return array_filter($potentialDelegates, function ($potentialDelegate) {
			return $potentialDelegate instanceof DelegatesImportsContract;
		});
	}
}