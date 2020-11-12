<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use Shomisha\Stubless\Contracts\Template as TemplateContract;

abstract class Template implements TemplateContract
{
	private BuilderFactory $factory;

	private PrettyPrinter $printer;

	public function save(string $path): bool
	{
		return file_put_contents($path, $this->print());
	}

	public function print(): string
	{
		return $this->getPrinter()->prettyPrintFile([$this->constructNode()]);
	}

	abstract public function constructNode(): Node;

	protected function getFactory(): BuilderFactory
	{
		if (!isset($this->factory)) {
			$this->factory = new BuilderFactory();
		}

		return $this->factory;
	}

	protected function getPrinter(): PrettyPrinter
	{
		if (!isset($this->printer)) {
			$this->printer = new PrettyPrinter();
		}

		return $this->printer;
	}

	protected function convertBuilderToNode(Builder $builder): Node
	{
		return $builder->getNode();
	}

	protected function validateArrayElements(array $array, string $expectedClass): void
	{
		foreach ($array as $element) {
			if (! $element instanceof $expectedClass) {
				throw new \InvalidArgumentException("Array contains values that are not an instance of the '{$expectedClass}' class.");
			}
		}
	}
}