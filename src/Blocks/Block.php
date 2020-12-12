<?php

namespace Shomisha\Stubless\Blocks;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Template;
use Shomisha\Stubless\Templates\UseStatement;
use Shomisha\Stubless\Values\Value;

class Block extends Template implements DelegatesImports
{
	use HasImports;

	/** @var \Shomisha\Stubless\Blocks\Block[] */
	private array $subBlocks;

	/** @param \Shomisha\Stubless\Blocks\Block[] $subBlocks */
	public function __construct(array $subBlocks = [])
	{
		$this->subBlocks = $subBlocks;
	}

	public static function fromArray(array $subBlocks): Block
	{
		return new self($subBlocks);
	}

	public function addBlock(Block $block): self
	{
		$this->subBlocks[] = $block;

		return $this;
	}

	public function addBlocks(array $blocks): self
	{
		$this->subBlocks = array_merge($this->subBlocks, $blocks);

		return $this;
	}

	public function print(): string
	{
		$importNodes = array_map(function (UseStatement $statement) {
			return $statement->getPrintableNodes()[0];
		}, $this->getDelegatedImports());

		$expressions = array_map(function (Node $node) {
			return new Expression($node);
		}, $this->getPrintableNodes());

		return $this->getFormatter()->format(
			$this->getPrinter()->prettyPrintFile(
				[...array_values($importNodes), ...$expressions]
			)
		);
	}

	public function getPrintableNodes(): array
	{
		$nodes = [];

		foreach ($this->subBlocks as $block) {
			$nodes = array_merge($nodes, $block->getPrintableNodes());
		}

		return $nodes;
	}

	public function getDelegatedImports(): array
	{
		if (!isset($this->subBlocks)) {
			return [];
		}

		return $this->gatherImportsFromDelegates(
			$this->extractImportDelegatesFromArray($this->subBlocks)
		);
	}

	public static function assign($variable, $value): AssignBlock
	{
		if (!$variable instanceof AssignableContainer) {
			if (is_string($variable)) {
				$variable = Variable::name($variable);
			} else {
				throw new \InvalidArgumentException(sprintf(
					"Method %s::assign() expects string or instance of %s as first argument.",
					self::class,
					AssignableContainer::class,
				));
			}
		}

		return new AssignBlock($variable, Value::normalize($value));
	}

	public static function return($returnValue): ReturnBlock
	{
		return new ReturnBlock(Value::normalize($returnValue));
	}

	/** @param \Shomisha\Stubless\Utilities\Importable|string $class */
	public static function instantiate($class): InstantiateBlock
	{
		return new InstantiateBlock($class);
	}

	public static function invokeMethod(Variable $variable, string $name, array $arguments = []): InvokeMethodBlock
	{
		return new InvokeMethodBlock($variable, $name, $arguments);
	}

	public static function invokeStaticMethod(ClassReference $class, string $name, array $arguments = []): InvokeStaticMethodBlock
	{
		return new InvokeStaticMethodBlock($class, $name, $arguments);
	}

	public static function invokeFunction(string $name, array $arguments = []): InvokeFunctionBlock
	{
		return new InvokeFunctionBlock($name, $arguments);
	}
}