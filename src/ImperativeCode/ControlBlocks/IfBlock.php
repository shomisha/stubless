<?php

namespace Shomisha\Stubless\ImperativeCode\ControlBlocks;

use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Values\AssignableValue;

class IfBlock extends ImperativeCode
{
	private const ELSEIF_KEY_CONDITION = 'condition';
	private const ELSEIF_KEY_BODY = 'body';

	private AssignableValue $condition;

	private ?ImperativeCode $body = null;

	private array $elseIfs = [];

	private ?ImperativeCode $elseBlock = null;

	public function __construct(AssignableValue $condition, ?ImperativeCode $body = null)
	{
		$this->condition = $condition;
		$this->body = $body;
	}

	public function then(?ImperativeCode $body): self
	{
		$this->body = $body;

		return $this;
	}

	public function elseif($condition, ?ImperativeCode $body): self
	{
		$this->elseIfs[] = [
			self::ELSEIF_KEY_CONDITION => AssignableValue::normalize($condition),
			self::ELSEIF_KEY_BODY => $body
		];

		return $this;
	}

	public function else(?ImperativeCode $body): self
	{
		$this->elseBlock = $body;

		return $this;
	}

	public function getPrintableNodes(): array
	{
		$elseIfs = array_map(function (array $elseIfConditionAndBody) {
			/** @var AssignableValue $condition */
			$condition = $elseIfConditionAndBody[self::ELSEIF_KEY_CONDITION];

			/** @var \Shomisha\Stubless\ImperativeCode\Block|null $block */
			$block = $elseIfConditionAndBody[self::ELSEIF_KEY_BODY];

			return new ElseIf_(
				$condition->getAssignableValueExpression(),
				$this->normalizeNodesToExpressions($block->getPrintableNodes())
			);
		}, $this->elseIfs);

		$body = [];
		if ($this->body) {
			$body = $this->normalizeNodesToExpressions($this->body->getPrintableNodes());
		}

		$else = null;
		if ($this->elseBlock) {
			$else = new Else_(
				$this->normalizeNodesToExpressions($this->elseBlock->getPrintableNodes())
			);
		}

		return [
			new If_(
				$this->condition->getAssignableValueExpression(),
				[
					'stmts' => $body,
					'elseifs' => $elseIfs,
					'else' => $else,
				]
			)
		];
	}

	protected function getImportSubDelegates(): array
	{
		$allElements = [
			$this->condition,
			$this->body,
			$this->elseBlock,
		];

		foreach ($this->elseIfs as $conditionAndBody) {
			$allElements[] = $conditionAndBody[self::ELSEIF_KEY_CONDITION];
			$allElements[] = $conditionAndBody[self::ELSEIF_KEY_BODY];
		}

		return $this->extractImportDelegatesFromArray($allElements);
	}
}