<?php

namespace Shomisha\Stubless\Abstractions;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use Shomisha\Stubless\Contracts\Code as CodeContract;
use Shomisha\Stubless\Contracts\Formatter;
use Shomisha\Stubless\Formatters\CsFixerFormatter;

abstract class Code implements CodeContract
{
	private BuilderFactory $factory;

	private PrettyPrinter $printer;

	private Formatter $formatter;

	public function save(string $path): bool
	{
		return file_put_contents($path, $this->print());
	}

	abstract public function print(): string;

	/** @return \PhpParser\Node[] */
	abstract public function getPrintableNodes(): array;

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

	protected function getFormatter(): Formatter
	{
		if (!isset($this->formatter)) {
			$this->formatter = new CsFixerFormatter();
		}

		return $this->formatter;
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