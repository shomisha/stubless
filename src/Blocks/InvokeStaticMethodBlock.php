<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Reference;

class InvokeStaticMethodBlock extends InvokeBlock
{
	private ClassReference $class;

	public function __construct(ClassReference $class, string $name, array $arguments = [])
	{
		parent::__construct($name, $arguments);

		$this->class = $class;
	}

	public function getPrintableNodes(): array
	{
		return [
			new StaticCall(new Name($this->class->getName()), $this->name, $this->normalizedArguments())
		];
	}

	public function getDelegatedImports(): array
	{
		return $this->gatherImportsFromDelegates([
			...$this->extractImportDelegatesFromArray($this->arguments),
			$this->class
		]);
	}
}