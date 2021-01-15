<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Expr;
use Shomisha\Stubless\Contracts\ObjectContainer;

class InvokeMethodBlock extends InvokeBlock
{
	private ObjectContainer $object;

	public function __construct(ObjectContainer $object, string $name, array $arguments = [])
	{
		parent::__construct($name, $arguments);

		$this->object = $object;
	}

	public function getInvokablePrintableNodes(): array
	{
		return [
			new Expr\MethodCall(
				$this->object->getPrintableNodes()[0],
				$this->name,
				$this->normalizedArguments()
			)
		];
	}
}