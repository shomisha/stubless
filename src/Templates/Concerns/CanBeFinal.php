<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder;

trait CanBeFinal
{
	protected bool $isFinal = false;

	public function makeFinal($final = true): self
	{
		$this->isFinal = $final;

		return $this;
	}

	public function isFinal(): bool
	{
		return $this->isFinal;
	}

	public function makeBuilderFinal(Builder $builder): void
	{
		if ($this->isFinal) {
			$builder->makeFinal();
		}
	}
}