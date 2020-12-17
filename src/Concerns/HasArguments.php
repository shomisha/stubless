<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder\FunctionLike;
use Shomisha\Stubless\DeclarativeCode\Argument;

/** @mixin \Shomisha\Stubless\Abstractions\DeclarativeCode */
trait HasArguments
{
	/** @var \Shomisha\Stubless\DeclarativeCode\Argument[] */
	protected array $arguments = [];

	/** @param \Shomisha\Stubless\DeclarativeCode\Argument[] $arguments */
	public function arguments(array $arguments = null)
	{
		if ($arguments === null) {
			return $this->getArguments();
		}

		return $this->withArguments($arguments);
	}

	public function addArgument(Argument $argument): self
	{
		$this->arguments[$argument->getName()] = $argument;

		return $this;
	}

	public function removeArgument(string $name): self
	{
		unset($this->arguments[$name]);

		return $this;
	}

	/** @param \Shomisha\Stubless\DeclarativeCode\Argument[] */
	public function withArguments(array $arguments): self
	{
		$this->validateArrayElements($arguments, Argument::class);

		$this->arguments = [];

		foreach ($arguments as $argument) {
			$this->addArgument($argument);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\DeclarativeCode\Argument[] */
	public function getArguments(): array
	{
		return $this->arguments;
	}

	protected function addArgumentsToFunctionLike(FunctionLike $functionLike): void
	{
		foreach ($this->arguments as $argument) {
			$functionLike->addParam($argument->getPrintableNodes()[0]);
		}
	}
}