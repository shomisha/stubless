<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder;

trait CanBeAbstract
{
	protected bool $isAbstract = false;

	public function abstract(bool $abstract = null)
	{
		if ($abstract === null) {
			return $this->isAbstract();
		}

		return $this->makeAbstract($abstract);
	}

	public function makeAbstract($abstract = true): self
	{
		$this->isAbstract = $abstract;

		return $this;
	}

	public function isAbstract(): bool
	{
		return $this->isAbstract;
	}

	/** @param \PhpParser\Builder\Method|\PhpParser\Builder\Class_ $builder */
	public function makeBuilderAbstract(Builder $builder): void
	{
		if ($this->isAbstract) {
			$builder->makeAbstract();
		}
	}
}