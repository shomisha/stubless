<?php

namespace Shomisha\Stubless\Test\Unit\Declarative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\DeclarativeCode\ClassProperty;
use Shomisha\Stubless\DeclarativeCode\ClassTemplate;
use Shomisha\Stubless\References\Reference;
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
	public function user_can_check_if_class_property_is_public()
	{
		$property = ClassProperty::name('test')->makeProtected();


		$isPublic = $property->isPublic();


		$this->assertFalse($isPublic);
	}

	/** @test */
	public function user_can_create_protected_class_property()
	{
		$property = ClassProperty::name('protectedProperty')->makeProtected();


		$printed = $property->print();


		$this->assertStringContainsString('protected $protectedProperty;', $printed);
	}

	/** @test */
	public function user_can_check_if_class_property_is_protected()
	{
		$property = ClassProperty::name('test')->makeProtected();


		$isProtected = $property->isProtected();


		$this->assertTrue($isProtected);
	}

	/** @test */
	public function user_can_create_private_class_property()
	{
		$property = ClassProperty::name('privateProperty')->makePrivate();


		$printed = $property->print();


		$this->assertStringContainsString('private $privateProperty;', $printed);
	}

	/** @test */
	public function user_can_check_if_class_property_is_private()
	{
		$property = ClassProperty::name('test')->makePublic();


		$isPrivate = $property->isPrivate();


		$this->assertFalse($isPrivate);
	}

	/** @test */
	public function user_can_set_property_access_modifier_explicitly()
	{
		$property = ClassProperty::name('test');


		$property->setAccess(ClassAccess::PROTECTED());


		$this->assertTrue($property->isProtected());
	}

	/** @test */
	public function user_can_get_property_access_modifier()
	{
		$property = ClassProperty::name('test')->makeProtected();


		$access = $property->getAccess();


		$this->assertInstanceOf(ClassAccess::class, $access);
		$this->assertEquals('protected', $access->value());
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
	public function user_cannot_set_objects_as_value_to_class_property()
	{
		$property = ClassProperty::name('test');


		$this->expectException(\InvalidArgumentException::class);


		$property->value(new \stdClass());
	}

	/** @test */
	public function user_can_user_importables_as_class_property_values()
	{
		$classTemplate = ClassTemplate::name('SomeClass')->withProperties([
			ClassProperty::name('anotherClassName')->value(new Importable('App\Services\AnotherClass'))
		]);

		$printed = $classTemplate->print();


		$this->assertStringContainsString('use App\Services\AnotherClass;', $printed);
		$this->assertStringContainsString("public \$anotherClassName = AnotherClass::class", $printed);
	}

	/** @test */
	public function user_can_use_class_references_as_class_property_values()
	{
		$classTemplate = ClassTemplate::name('SomeCLass')->withProperties([
			ClassProperty::name('anotherClassName')->value(Reference::classReference('AnotherClass'))
		]);


		$printed = $classTemplate->print();


		$this->assertStringContainsString('public $anotherClassName = AnotherClass::class', $printed);
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

	/** @test */
	public function user_can_make_properties_static()
	{
		$property = ClassProperty::name('staticProperty');


		$property->makeStatic();
		$printed = $property->print();


		$this->assertStringContainsString('public static $staticProperty;', $printed);
	}

	/** @test */
	public function user_can_make_properties_static_using_the_fluent_alias()
	{
		$property = ClassProperty::name('staticProperty');


		$property->static(true);
		$printed = $property->print();


		$this->assertStringContainsString('public static $staticProperty;', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 * 			 [false]
	 */
	public function user_can_check_if_properties_are_static($shouldBeStatic)
	{
		$property = ClassProperty::name('staticProperty')->makeStatic($shouldBeStatic);


		$isStatic = $property->isStatic();


		$this->assertEquals($shouldBeStatic, $isStatic);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function user_can_check_if_properties_are_static_using_the_fluent_alias($shouldBeStatic)
	{
		$property = ClassProperty::name('staticProperty')->makeStatic($shouldBeStatic);


		$isStatic = $property->static();


		$this->assertEquals($shouldBeStatic, $isStatic);
	}
}