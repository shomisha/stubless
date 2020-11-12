<?php

namespace Shomisha\Stubless\Enums;

class ClassAccess extends BaseEnum
{
	public static function PUBLIC(): self
	{
		return new self('public');
	}

	public static function PROTECTED(): self
	{
		return new self('protected');
	}

	public static function PRIVATE(): self
	{
		return new self('private');
	}

	public static function all(): array
	{
		return [

		];
	}
}