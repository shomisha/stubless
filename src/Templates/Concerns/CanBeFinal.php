<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder;

trait CanBeFinal
{
	protected bool $isFinal = false;

	public function final(bool $isFinal = null)
	{
		if ($isFinal === null) {
			return $this->isFinal();
		}

		return $this->makeFinal($isFinal);
	}

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