<?php

namespace Shomisha\Stubless\Abstractions;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use Shomisha\Stubless\Concerns\DelegatesImports as DelegatesImportsConcern;
use Shomisha\Stubless\Concerns\HasImports;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\DeclarativeCode\UseStatement;

abstract class ImperativeCode extends Code implements DelegatesImports
{
	use HasImports, DelegatesImportsConcern;

	public function print(): string
	{
		$importNodes = array_map(function (UseStatement $statement) {
			return $statement->getPrintableNodes()[0];
		}, $this->getDelegatedImports());

		$expressions = array_map(function (Node $node) {
			if ($node instanceof Node\Stmt) {
				return $node;
			}

			return new Expression($node);
		}, $this->getPrintableNodes());

		return $this->getFormatter()->format(
			$this->getPrinter()->prettyPrintFile(
				[...array_values($importNodes), ...$expressions]
			)
		);
	}
}