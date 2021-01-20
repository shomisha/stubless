<?php

namespace Shomisha\Stubless\Abstractions;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use Shomisha\Stubless\Concerns\DelegatesImports as DelegatesImportsConcern;
use Shomisha\Stubless\Concerns\HasImports;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\ImperativeCode\UseStatement;

abstract class ImperativeCode extends Code implements DelegatesImports
{
	use HasImports, DelegatesImportsConcern;

	public function print(): string
	{
		$importNodes = array_map(function (UseStatement $statement) {
			return $statement->getPrintableNodes()[0];
		}, $this->getDelegatedImports());

		$expressions = $this->normalizeNodesToExpressions($this->getPrintableNodes());

		return $this->getFormatter()->format(
			$this->getPrinter()->prettyPrintFile(
				[...array_values($importNodes), ...$expressions]
			)
		);
	}

	protected function normalizeNodesToExpressions(array $nodes): array
	{
		return array_map(function (Node $node) {
			if ($node instanceof Node\Stmt) {
				return $node;
			}

			return new Expression($node);
		}, $nodes);
	}
}