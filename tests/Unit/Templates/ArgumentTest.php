<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Templates\ClassTemplate;

class ArgumentTest extends TestCase
{
	/** @test */
	public function users_can_create_arguments_with_name_and_type()
	{
		$argument = Argument::name('test')->type('string');


		$printed = $argument->print();


		$this->assertStringContainsString('string $test', $printed);
	}

	/** @test */
	public function users_can_create_argument_without_type()
	{
		$argument = Argument::name('test');


		$printed = $argument->print();


		$this->assertEquals("<?php\n\n\$test", $printed);
	}

	/** @test */
	public function user_can_get_argument_type()
	{
		$argument = Argument::name('test')->type('int');


		$type = $argument->getType();


		$this->assertEquals('int', $type);
	}

	/** @test */
	public function user_can_get_argument_type_using_fluent_alias()
	{
		$argument = Argument::name('test')->type('array');


		$type = $argument->type();


		$this->assertEquals('array', $type);
	}
}