<?php

namespace Shomisha\Stubless\ImperativeCode;

use PhpParser\Node\Stmt\Throw_;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Contracts\ObjectContainer;

class ThrowBlock extends ImperativeCode
{
	private ObjectContainer $exception;

	public function __construct(ObjectContainer $exception)
	{
		$this->exception = $exception;
	}

	public function getPrintableNodes(): array
	{
		return [
			new Throw_($this->exception->getAssignableValueExpression())
		];
	}

	protected function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray([
			$this->exception
		]);
	}
}