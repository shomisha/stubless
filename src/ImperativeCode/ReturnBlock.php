<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Stmt\Return_;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Values\AssignableValue;

class ReturnBlock extends ImperativeCode
{
	private AssignableValue $value;

	public function __construct(AssignableValue $returnValue)
	{
		$this->value = $returnValue;
	}

	public function getPrintableNodes(): array
	{
		return [new Return_($this->value->getAssignableValueExpression())];
	}

	public function print(): string
	{
		return $this->getFormatter()->format(
			$this->getPrinter()->prettyPrintFile(
				$this->getPrintableNodes()
			)
		);
	}

	public function getImportSubDelegates(): array
	{
		return [$this->value];
	}
}