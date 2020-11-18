<?php

namespace Shomisha\Stubless\Enums;

abstract class BaseEnum
{
	private string $value;

	public function __construct(string $value)
	{
		$this->value = $value;
	}

	public function __toString()
	{
		return $this->value;
	}

	public function value(): string
	{
		return $this->value;
	}

	public static function fromString(string $value): self
	{
		if (!in_array($value, static::all())) {
			throw new \InvalidArgumentException(
				sprintf("Invalid enum value '%s' for enum %s", $value, static::class)
			);
		}

		return new static($value);
	}

	abstract public static function all(): array;
}