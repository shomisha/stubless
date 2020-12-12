<?php

namespace Shomisha\Stubless\Enums;

use PhpParser\Node\Stmt\Class_;

class ClassAccess extends BaseEnum
{
	protected int $phpParserAccessModifier;

	protected function __construct(string $value, int $phpParserAccessModifier)
	{
		parent::__construct($value);
		$this->phpParserAccessModifier = $phpParserAccessModifier;
	}

	public function getPhpParserAccessModifier(): int
	{
		return $this->phpParserAccessModifier;
	}

	public static function fromString(string $value): BaseEnum
	{
		switch ($value) {
			case 'public':
				return self::PUBLIC();
			case 'protected':
				return self::PROTECTED();
			case 'private':
				return self::PRIVATE();
		}

		throw new \InvalidArgumentException("Invalid class access provided: {$value}");
	}

	public static function PUBLIC(): self
	{
		return new self('public', Class_::MODIFIER_PUBLIC);
	}

	public static function PROTECTED(): self
	{
		return new self('protected', Class_::MODIFIER_PROTECTED);
	}

	public static function PRIVATE(): self
	{
		return new self('private', Class_::MODIFIER_PRIVATE);
	}

	public static function all(): array
	{
		return [
			self::PUBLIC(),
			self::PROTECTED(),
			self::PRIVATE(),
		];
	}
}