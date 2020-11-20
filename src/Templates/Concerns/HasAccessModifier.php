<?php

namespace Shomisha\Stubless\Templates\Concerns;

use PhpParser\Builder;
use Shomisha\Stubless\Enums\ClassAccess;

trait HasAccessModifier
{
	protected ClassAccess $access;

	public function setAccess(ClassAccess $access): self
	{
		$this->access = $access;

		return $this;
	}

	public function getAccess(): ClassAccess
	{
		return $this->access;
	}

	public function makePublic(): self
	{
		$this->access = ClassAccess::PUBLIC();

		return $this;
	}

	public function isPublic(): bool
	{
		return $this->access == ClassAccess::PUBLIC();
	}

	public function makeProtected(): self
	{
		$this->access = ClassAccess::PROTECTED();

		return $this;
	}

	public function isProtected(): bool
	{
		return $this->access == ClassAccess::PROTECTED();
	}

	public function makePrivate(): self
	{
		$this->access = ClassAccess::PRIVATE();

		return $this;
	}

	public function isPrivate(): bool
	{
		return $this->access == ClassAccess::PRIVATE();
	}

	/** @param \PhpParser\Builder\Method|\PhpParser\Builder\Property $builder */
	private function setAccessToBuilder(Builder $builder): void
	{
		switch ($this->access) {
			case ClassAccess::PUBLIC():
				$builder->makePublic();
				return;
			case ClassAccess::PROTECTED():
				$builder->makeProtected();
				return;
			case ClassAccess::PRIVATE():
				$builder->makePrivate();
				return;
		}
	}
}