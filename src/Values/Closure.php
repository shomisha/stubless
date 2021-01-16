<?php

namespace Shomisha\Stubless\Values;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure as PhpParserClosure;
use PhpParser\Node\Stmt\Expression;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\DeclarativeCode\Argument;
use Shomisha\Stubless\References\Variable;

class Closure extends AssignableValue
{
	/** @var \Shomisha\Stubless\DeclarativeCode\Argument[] */
	private array $arguments;

	/** @var \Shomisha\Stubless\References\Variable[] */
	private array $uses = [];
	
	private ?ImperativeCode $body = null;
	
	public function __construct(array $arguments, ?ImperativeCode $body = null)
	{
		$this->arguments = $arguments;
		$this->body = $body;
	}

	public function uses(Variable $variable): self
	{
		$this->uses[] = $variable;

		return $this;
	}

	public function getPrintableNodes(): array
	{
		$arguments = array_map(function (Argument $argument) {
			return $argument->getPrintableNodes()[0];
		}, $this->arguments);

		$uses = array_map(function (Variable $variable) {
			return $variable->getPrintableNodes()[0];
		}, $this->uses);

		$body = [];
		if ($this->body !== null) {
			$body = $this->normalizeNodesToExpressions($this->body->getPrintableNodes());
		}

		return [
			new PhpParserClosure([
				'params' => $arguments,
				'uses' => $uses,
				'stmts' => $body,
			]),
		];
	}

	protected function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray([
			...$this->arguments,
			$this->body
		]);
	}
}