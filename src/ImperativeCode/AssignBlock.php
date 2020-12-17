<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Expr\Assign;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\Values\AssignableValue;

class AssignBlock extends ImperativeCode
{
	private AssignableContainer $container;

	private AssignableValue $value;

	public function __construct(AssignableContainer $container, AssignableValue $value)
	{
		$this->container = $container;
		$this->value = $value;
	}

	public function getPrintableNodes(): array
	{
		return [
			new Assign(
				$this->container->getAssignableContainerExpression(),
				$this->value->getAssignableValueExpression()
			)
		];
	}

	protected function getImportSubDelegates(): array
	{
		return [
			$this->container,
			$this->value,
		];
	}
}