<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\BuilderHelpers;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\Values\AssignableValue;

abstract class InvokeBlock extends AssignableValue implements DelegatesImports
{
	protected string $name;

	protected array $arguments;

	public function __construct(string $name, array $arguments = [])
	{
		$this->name = $name;
		$this->arguments = $arguments;
	}

	/** @return \PhpParser\Node\Arg[] */
	protected function normalizedArguments(): array
	{
		return array_map(function ($argument) {
			if ($argument instanceof AssignableValue) {
				return new Arg($argument->getAssignableValueExpression());
			}

			return BuilderHelpers::normalizeValue($argument);
		}, $this->arguments);
	}

	public function getDelegatedImports(): array
	{
		return $this->gatherImportsFromDelegates(
			$this->extractImportDelegatesFromArray($this->arguments)
		);
	}
}