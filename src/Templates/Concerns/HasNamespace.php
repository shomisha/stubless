<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder\Namespace_;

/** @mixin \Shomisha\Stubless\Templates\Template */
trait HasNamespace
{
	protected ?string $namespace = null;

	public function hasNamespace(): bool
	{
		return $this->namespace !== null;
	}

	public function getNamespace(): ?string
	{
		return $this->namespace;
	}

	public function setNamespace(?string $namespace): self
	{
		$this->namespace = $namespace;

		return $this;
	}

	protected function getNamespaceBuilder(): Namespace_
	{
		return $this->getFactory()->namespace($this->namespace);
	}
}