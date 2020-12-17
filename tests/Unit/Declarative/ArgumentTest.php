<?php

namespace Shomisha\Stubless\Test\Unit\Declarative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\DeclarativeCode\Argument;
use Shomisha\Stubless\DeclarativeCode\ClassTemplate;

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

	/** @test */
	public function users_can_add_default_values_to_arguments()
	{
		$argument = Argument::name('test')->type('string');


		$argument->setValue('testValue');
		$printed = $argument->print();


		$this->assertStringContainsString("string \$test = 'testValue'", $printed);
	}

	/** @test */
	public function users_cannot_use_objects_as_argument_default_values()
	{
		$argument = Argument::name('test');


		$this->expectException(\InvalidArgumentException::class);


		$argument->setValue(new \stdClass());
	}

	/** @test */
	public function users_can_add_default_value_to_arguments_using_the_fluent_alias()
	{
		$argument = Argument::name('test')->type('string');


		$argument->value('testValue');
		$printed = $argument->print();


		$this->assertStringContainsString("string \$test = 'testValue'", $printed);
	}

	/** @test */
	public function users_can_get_the_default_value_from_arguments()
	{
		$argument = Argument::name('test')->value(15);


		$value = $argument->getValue();


		$this->assertEquals(15, $value);
	}

	/** @test */
	public function users_can_get_the_default_value_from_arguments_using_the_fluent_alias()
	{
		$argument = Argument::name('test')->value([1, 2, 3]);


		$value = $argument->value();


		$this->assertEquals([1, 2, 3], $value);
	}
}