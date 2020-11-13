<?php

namespace Shomisha\Stubless\Templates\Concerns;

trait HasName
{
	protected string $name;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public static function name(string $name): self
	{
		return new static($name);
	}
}