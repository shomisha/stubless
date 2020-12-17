<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use Shomisha\Stubless\References\ClassReference;

class InvokeStaticMethodBlock extends InvokeBlock
{
	private ClassReference $class;

	public function __construct(ClassReference $class, string $name, array $arguments = [])
	{
		parent::__construct($name, $arguments);

		$this->class = $class;
	}

	public function getInvokablePrintableNodes(): array
	{
		return [
			new StaticCall(new Name($this->class->getName()), $this->name, $this->normalizedArguments())
		];
	}

	public function getImportSubDelegates(): array
	{
		return array_merge(
			parent::getImportSubDelegates(),
			[$this->class]
		);
	}
}