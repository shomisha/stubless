<?php

namespace Shomisha\Stubless\Concerns;

use Shomisha\Stubless\Contracts\DelegatesImports as DelegatesImportsContract;

/** @see \Shomisha\Stubless\Contracts\DelegatesImports */
/** @mixin \Shomisha\Stubless\Concerns\HasImports */
trait DelegatesImports
{
	/** @return \Shomisha\Stubless\DeclarativeCode\UseStatement[] */
	public function getDelegatedImports(): array
	{
		$imports = $this->getImports();

		foreach ($this->getImportSubDelegates() as $subDelegate) {
			$imports = array_merge($imports, $subDelegate->getDelegatedImports());
		}

		return $imports;
	}

	protected function getImportSubDelegates(): array
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