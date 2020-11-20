<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\ClassConstant;
use Shomisha\Stubless\Templates\ClassTemplate;

class ClassConstantTest extends TestCase
{
	/** @test */
	public function user_can_create_class_constants()
	{
		$constant = ClassConstant::name('TEST')->value(15);


		$printed = $constant->print();


		$this->assertStringContainsString('public const TEST = 15;', $printed);
	}

	/** @test */
	public function user_can_create_public_class_constants()
	{
		$constant = ClassConstant::name('TEST')->value(false);
		$constant->makePublic();


		$printed = $constant->print();


		$this->assertStringContainsString('public const TEST = false;', $printed);
	}

	/** @test */
	public function user_can_create_protected_class_constants()
	{
		$constant = ClassConstant::name('PROTECTED_CONSTANT')->value('test');

		$constant->makeProtected();


		$printed = $constant->print();


		$this->assertStringContainsString('protected const PROTECTED_CONSTANT = \'test\';', $printed);
	}

	/** @test */
	public function user_can_create_private_class_constants()
	{
		$constant = ClassConstant::name('TEST')->value(12);

		$constant->makePrivate();


		$printed = $constant->print();


		$this->assertStringContainsString('private const TEST = 12;', $printed);
	}
}