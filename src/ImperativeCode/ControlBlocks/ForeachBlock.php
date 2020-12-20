<?php

namespace Shomisha\Stubless\ImperativeCode\ControlBlocks;

use PhpParser\Node\Stmt\Foreach_;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\References\Variable;

class ForeachBlock extends ImperativeCode
{
	private Arrayable $arrayable;

	private ?Variable $keyContainer = null;

	private Variable $valueContainer;

	private ?ImperativeCode $body = null;

	public function __construct(Arrayable $arrayable, Variable $valueContainer, ?ImperativeCode $body = null)
	{
		$this->arrayable = $arrayable;
		$this->valueContainer = $valueContainer;
		$this->body = $body;
	}

	public function withKey(?Variable $keyVariable): self
	{
		$this->keyContainer = $keyVariable;

		return $this;
	}

	public function do(?ImperativeCode $body): self
	{
		$this->body = $body;

		return $this;
	}

	public function getPrintableNodes(): array
	{
		$subNodes = [];

		if ($this->keyContainer !== null) {
			$subNodes['keyVar'] = $this->keyContainer->getAssignableValueExpression();
		}

		if ($this->body !==  null) {
			$subNodes['stmts'] = $this->normalizeNodesToExpressions($this->body->getPrintableNodes());
		}

		return [
			new Foreach_(
				$this->arrayable->getPrintableArrayExpr(),
				$this->valueContainer->getAssignableValueExpression(),
				$subNodes,
			),
		];
	}

	protected function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray([
			$this->arrayable,
			$this->body,
		]);
	}
}