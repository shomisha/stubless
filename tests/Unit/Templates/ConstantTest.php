<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\Constant;

class ConstantTest extends TestCase
{
	/** @test */
	public function user_can_create_constants()
	{
		$constant = Constant::name('TEST')->value(212);


		$printed = $constant->print();


		$this->assertStringContainsString('const TEST = 212;', $printed);
	}

	/** @test */
	public function user_can_get_constant_value()
	{
		$constant = Constant::name('TEST')->value(3.14);


		$value = $constant->getValue();


		$this->assertEquals(3.14, $value);
	}

	/** @test */
	public function user_cannot_set_invalid_values_to_constants()
	{
		$this->expectException(\InvalidArgumentException::class);

		Constant::name('TEST')->value(new \stdClass());
	}
}