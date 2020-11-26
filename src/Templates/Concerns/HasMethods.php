<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\Templates\ClassMethod;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasMethods
{
	/** @var \Shomisha\Stubless\Templates\ClassMethod[] */
	private array $methods = [];

	/** @param \Shomisha\Stubless\Templates\ClassMethod[] $methods */
	public function methods(array $methods = null)
	{
		if ($methods === null) {
			return $this->getMethods();
		}

		return $this->withMethods($methods);
	}

	public function addMethod(ClassMethod $method): self
	{
		$this->methods[$method->getName()] = $method;

		return $this;
	}

	public function removeMethod(string $name): self
	{
		unset($this->methods[$name]);

		return $this;
	}

	/** @param \Shomisha\Stubless\Templates\ClassMethod[] $methods */
	public function withMethods(array $methods): self
	{
		$this->validateArrayElements($methods, ClassMethod::class);

		$this->methods = [];

		foreach ($methods as $method) {
			$this->addMethod($method);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\Templates\ClassMethod[] */
	public function getMethods(): array
	{
		return $this->methods;
	}

	protected function addMethodsToDeclaration(Declaration $declaration): void
	{
		foreach ($this->methods as $method) {
			$declaration->addStmt($method->constructNode());
		}
	}
}