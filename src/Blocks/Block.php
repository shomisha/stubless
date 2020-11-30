<?php

namespace Shomisha\Stubless\Blocks;

use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Template;

class Block extends Template implements DelegatesImports
{
	use HasImports;

	/** @var \Shomisha\Stubless\Blocks\Block[] */
	private array $subBlocks;

	/** @param \Shomisha\Stubless\Blocks\Block[] $subBlocks */
	public function __construct(array $subBlocks)
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
		return $this->gatherImportsFromDelegates(
			$this->extractImportDelegatesFromArray($this->subBlocks)
		);
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