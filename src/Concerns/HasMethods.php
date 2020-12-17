<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder\Declaration;
use Shomisha\Stubless\DeclarativeCode\ClassMethod;

/** @mixin \Shomisha\Stubless\Abstractions\DeclarativeCode */
trait HasMethods
{
	/** @var \Shomisha\Stubless\DeclarativeCode\ClassMethod[] */
	private array $methods = [];

	/** @param \Shomisha\Stubless\DeclarativeCode\ClassMethod[] $methods */
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

	/** @param \Shomisha\Stubless\DeclarativeCode\ClassMethod[] $methods */
	public function withMethods(array $methods): self
	{
		$this->validateArrayElements($methods, ClassMethod::class);

		$this->methods = [];

		foreach ($methods as $method) {
			$this->addMethod($method);
		}

		return $this;
	}

	/** @return \Shomisha\Stubless\DeclarativeCode\ClassMethod[] */
	public function getMethods(): array
	{
		return $this->methods;
	}

	protected function addMethodsToDeclaration(Declaration $declaration): void
	{
		foreach ($this->methods as $method) {
			$declaration->addStmts($method->getPrintableNodes());
		}
	}
}