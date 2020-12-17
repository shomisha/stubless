<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder\Namespace_;

/** @mixin \Shomisha\Stubless\Abstractions\DeclarativeCode */
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
}