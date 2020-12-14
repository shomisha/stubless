<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use Shomisha\Stubless\Contracts\DelegatesImports;

class InstantiateBlock extends InvokeBlock implements DelegatesImports
{
	public function __construct($class, array $arguments = [])
	{
		if ($this->isImportable($class)) {
			$this->addImportable($class);
		}

		parent::__construct((string) $class, $arguments);
	}

	public function getInvokablePrintableNodes(): array
	{
		return [
			new New_(new Name($this->name), $this->normalizedArguments()),
		];
	}

	public function getDelegatedImports(): array
	{
		return $this->imports;
	}
}