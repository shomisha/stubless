<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\ClassProperty;
use Shomisha\Stubless\Templates\ClassTemplate;
use Shomisha\Stubless\Utilities\Importable;

class ClassPropertyTest extends TestCase
{
	/** @test */
	public function user_can_create_class_property_with_all_characteristics()
	{
		$property = ClassProperty::name('testProperty');

		$property->makePrivate();

		$property->type('string');

		$property->value('testValue');


		$printed = $property->print();


		$this->assertStringContainsString('private string $testProperty = \'testValue\';', $printed);
	}

	/** @test */
	public function user_can_create_public_class_property()
	{
		$property = ClassProperty::name('publicProperty')->makePublic();


		$printed = $property->print();


		$this->assertStringContainsString('public $publicProperty;', $printed);
	}

	/** @test */
	public function user_can_create_protected_class_property()
	{
		$property = ClassProperty::name('protectedProperty')->makeProtected();


		$printed = $property->print();


		$this->assertStringContainsString('protected $protectedProperty;', $printed);
	}

	/** @test */
	public function user_can_create_private_class_property()
	{
		$property = ClassProperty::name('privateProperty')->makePrivate();


		$printed = $property->print();


		$this->assertStringContainsString('private $privateProperty;', $printed);
	}

	/** @test */
	public function user_can_create_class_property_with_importable_type()
	{
		$property = ClassProperty::name('someProperty')->type(
			new Importable(\App\Models\Vehicle::class)
		);

		$class = ClassTemplate::name('TestClass')->setNamespace('Test');

	    $class->addProperty($property);


		$printed = $class->print();


		$this->assertStringContainsString('use App\Models\Vehicle;', $printed);
		$this->assertStringContainsString('public Vehicle $someProperty;', $printed);
	}

	/** @test */
	public function user_can_create_class_property_without_type()
	{
		$property = ClassProperty::name('test');


		$printed = $property->print();


		$this->assertStringContainsString('public $test;', $printed);
	}

	/** @test */
	public function user_can_get_the_class_property_type()
	{
		$property = ClassProperty::name('test');

		$property->setType('string');


		$type = $property->getType();


		$this->assertEquals('string', $type);
	}

	/** @test */
	public function user_can_get_the_class_property_type_using_fluent_setter()
	{
		$property = ClassProperty::name('test');

		$property->setType('array');


		$type = $property->type();


		$this->assertEquals('array', $type);
	}

	/** @test */
	public function user_can_create_class_property_without_value()
	{
		$property = ClassProperty::name('test');


		$printed = $property->print();


		$this->assertStringContainsString('public $test;', $printed);
	}

	/** @test */
	public function user_can_get_the_class_property_value()
	{
		$property = ClassProperty::name('test')->setValue(15);


		$value = $property->getValue();


		$this->assertEquals(15, $value);
	}

	/** @test */
	public function user_can_get_the_class_property_value_using_fluent_setter()
	{
		$property = ClassProperty::name('test')->setValue(false);


		$value = $property->value();


		$this->assertEquals(false, $value);
	}
}