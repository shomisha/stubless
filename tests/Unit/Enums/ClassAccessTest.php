<?php

namespace Shomisha\Stubless\Test\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Enums\ClassAccess;

class ClassAccessTest extends TestCase
{
	/** @test */
	public function class_access_can_be_created_using_named_constructor()
	{
		$access = ClassAccess::PUBLIC();


		$this->assertInstanceOf(ClassAccess::class, $access);
	}

	/** @test */
	public function all_class_accesses_can_be_returned_at_once()
	{
		$classAccesses = ClassAccess::all();


		$this->assertEquals(ClassAccess::PUBLIC(), $classAccesses[0]);
		$this->assertEquals(ClassAccess::PROTECTED(), $classAccesses[1]);
		$this->assertEquals(ClassAccess::PRIVATE(), $classAccesses[2]);
	}

	/** @test */
	public function class_access_can_be_cast_to_string()
	{
		$access = ClassAccess::PROTECTED();


		$string = (string) $access;


		$this->assertEquals('protected', $string);
	}

	public function classAccessStringDataProvider() {
		return [
			'public' => ['public', ClassAccess::PUBLIC()],
			'protected' => ['protected', ClassAccess::PROTECTED()],
			'private' => ['private', ClassAccess::PRIVATE()],
		];
	}

	/**
	 * @test
	 * @dataProvider classAccessStringDataProvider
	 */
	public function class_access_can_be_created_from_string($string, $expectedAccess)
	{
		$actualAccess = ClassAccess::fromString($string);


		$this->assertEquals($actualAccess, $expectedAccess);
	}

	/** @test */
	public function class_access_cannot_be_created_from_invalid_string()
	{
		$string = 'semi-public';


		$this->expectException(\InvalidArgumentException::class);


		ClassAccess::fromString($string);
	}

	/** @test */
	public function class_access_can_return_its_name()
	{
		$access = ClassAccess::PRIVATE();


		$name = $access->value();


		$this->assertEquals('private', $name);
	}
}