<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder;

trait HasValue
{
	/** @var mixed */
	protected $value;

	public function value($value = null)
	{
		if ($value === null) {
			return $this->getValue();
		}

		return $this->setValue($value);
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value): self
	{
		$this->assertValueIsValid($value);

		$this->value = $value;

		return $this;
	}

	protected function assertValueIsValid($value)
	{
		if (is_object($value)) {
			throw new \InvalidArgumentException(sprintf("You cannot assign objects as values to %s.", static::class));
		}
	}
}