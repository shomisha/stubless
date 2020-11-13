<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\FunctionLike;
use Shomisha\Stubless\Templates\Argument;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasArguments
{
	/** @var \Shomisha\Stubless\Templates\Argument[] */
	protected array $arguments = [];

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

	/** @param \Shomisha\Stubless\Templates\Argument[] */
	public function withArguments(array $arguments): self
	{
		$this->validateArrayElements($arguments, Argument::class);

		$this->arguments = $arguments;

		return $this;
	}

	protected function addArgumentsToFunctionLike(FunctionLike $functionLike): void
	{
		foreach ($this->arguments as $argument) {
			$functionLike->addParam($argument->constructNode());
		}
	}
}