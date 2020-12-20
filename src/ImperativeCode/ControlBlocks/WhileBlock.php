<?php

namespace Shomisha\Stubless\ImperativeCode\ControlBlocks;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\While_;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Values\AssignableValue;

class WhileBlock extends ImperativeCode
{
	protected AssignableValue $condition;

	protected ?ImperativeCode $body = null;

	public function __construct(AssignableValue $condition, ?ImperativeCode $body = null)
	{
		$this->condition = $condition;
		$this->body = $body;
	}

	public function do(?ImperativeCode $body): self
	{
		$this->body = $body;

		return $this;
	}

	public function getPrintableNodes(): array
	{
		return [
			new While_(
				$this->getPrintableConditionExpression(),
				$this->getPrintableBodyExpressions(),
			)
		];
	}

	protected function getPrintableConditionExpression(): Expr
	{
		return $this->condition->getAssignableValueExpression();
	}

	protected function getPrintableBodyExpressions(): array
	{
		if ($this->body === null) {
			return [];
		}

		return $this->normalizeNodesToExpressions(
			$this->body->getPrintableNodes()
		);
	}

	protected function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray([
			$this->condition,
			$this->body
		]);
	}
}