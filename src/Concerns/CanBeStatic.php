<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder;

trait CanBeStatic
{
	protected bool $isStatic = false;

	public function static(bool $isStatic = null)
	{
		if ($isStatic !== null) {
			return $this->makeStatic($isStatic);
		}

		return $this->isStatic();
	}

	public function makeStatic(bool $isStatic = true): self
	{
		$this->isStatic = $isStatic;

		return $this;
	}

	public function isStatic(): bool
	{
		return $this->isStatic;
	}

	/** @param \PhpParser\Builder\Method|\PhpParser\Builder\Property $builder */
	public function makeBuilderStatic(Builder $builder): self
	{
		if ($this->isStatic) {
			$builder->makeStatic();
		}

		return $this;
	}
}