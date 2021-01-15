<?php

namespace Shomisha\Stubless\Test\Unit;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Contracts\ObjectContainer;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\References\ArrayKeyReference;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\ObjectProperty;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\SelfReference;
use Shomisha\Stubless\References\StaticProperty;
use Shomisha\Stubless\References\StaticReference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\DeclarativeCode\Argument;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\Value;

class ReferenceTest extends TestCase
{
	use ImperativeCodeDataProviders;

	/** @test */
	public function user_can_create_variable_reference_using_direct_constructor()
	{
		$variable = new Variable('test');


		$printed = $variable->print();


		$this->assertStringContainsString('$test', $printed);
	}

	/** @test */
	public function user_can_create_variable_reference_using_reference_factory()
	{
		$variable = Reference::variable('test');


		$printed = $variable->print();


		$this->assertStringContainsString('$test', $printed);
	}

	/** @test */
	public function user_can_create_variable_reference_from_argument()
	{
		$variable = Variable::fromArgument(Argument::name('test'));

		$this->assertInstanceOf(Variable::class, $variable);


		$printed = $variable->print();


		$this->assertStringContainsString('$test', $printed);
	}

	/** @test */
	public function user_can_create_this_reference_using_direct_constructor()
	{
		$thisReference = new This();


		$printed = $thisReference->print();


		$this->assertStringContainsString('$this', $printed);
	}

	/** @test */
	public function user_can_create_this_reference_using_reference_factory()
	{
		$thisReference = Reference::this();


		$printed = $thisReference->print();


		$this->assertStringContainsString('$this', $printed);
	}

	/** @test */
	public function user_can_create_object_property_reference_using_direct_constructor()
	{
		$objectProperty = new ObjectProperty(Variable::name('test'), 'first_attribute');


		$printed = $objectProperty->print();


		$this->assertStringContainsString('$test->first_attribute', $printed);
	}

	/** @test */
	public function user_can_create_object_property_using_this()
	{
		$objectProperty = new ObjectProperty(new This(), 'first_name');


		$printed = $objectProperty->print();


		$this->assertStringContainsString('$this->first_name', $printed);
	}

	/** @test */
	public function user_can_create_object_property_reference_using_reference_factory()
	{
		$objectProperty = Reference::objectProperty(Variable::name('user'), 'email');


		$printed = $objectProperty->print();


		$this->assertStringContainsString('$user->email', $printed);
	}

	/**
	 * @test
	 * @dataProvider objectContainersDataProvider
	 */
	public function user_can_create_object_property_using_any_object_container(ObjectContainer $object, string $printedObjectContainer)
	{
		$objectProperty = Reference::objectProperty($object, 'someProperty');


		$printed = $objectProperty->print();


		$this->assertStringContainsString("{$printedObjectContainer}->someProperty", $printed);
	}

	/** @test */
	public function user_can_create_static_property_reference_using_direct_constructor()
	{
		$staticProperty = new StaticProperty(Reference::classReference('App\Models\User'), 'totalCount');


		$printed = $staticProperty->print();


		$this->assertStringContainsString('App\Models\User::$totalCount', $printed);
	}

	/** @test */
	public function user_can_create_static_property_reference_using_reference_factory()
	{
		$staticProperty = Reference::staticProperty('App\Models\Income', 'overallIncome');


		$printed = $staticProperty->print();


		$this->assertStringContainsString('App\Models\Income::$overallIncome', $printed);
	}

	/** @test */
	public function user_can_create_static_property_reference_using_importable()
	{
		$staticProperty = Reference::staticProperty(new Importable('App\Models\Animals'), 'uniqueSpecies');


		$printed = $staticProperty->print();


		$this->assertStringContainsString('Animals::$uniqueSpecies', $printed);
	}

	/** @test */
	public function user_can_create_static_property_reference_using_string_as_class_name()
	{
		$staticProperty = Reference::staticProperty('User', 'databaseConnection');


		$printed = $staticProperty->print();


		$this->assertStringContainsString('User::$databaseConnection;', $printed);
	}

	/** @test */
	public function user_can_create_static_reference_using_direct_constructor()
	{
		$staticReference = new StaticReference();


		$printed = $staticReference->print();


		$this->assertStringContainsString('static::class', $printed);
	}

	/** @test */
	public function user_can_create_static_reference_using_reference_factory()
	{
		$staticReference = Reference::staticReference();


		$printed = $staticReference->print();


		$this->assertStringContainsString('static::class', $printed);
	}

	/** @test */
	public function user_can_create_self_reference_using_direct_constructor()
	{
		$selfReference = new SelfReference();


		$printed = $selfReference->print();


		$this->assertStringContainsString('self::class', $printed);
	}

	/** @test */
	public function user_can_create_self_reference_using_reference_factory()
	{
		$selfReference = Reference::selfReference();


		$printed = $selfReference->print();


		$this->assertStringContainsString('self::class', $printed);
	}

	/** @test */
	public function user_can_create_class_reference_using_direct_constructor()
	{
		$class = new ClassReference('Test\Unit\ReferenceTest');


		$printed = $class->print();


		$this->assertStringContainsString('Test\Unit\ReferenceTest::class', $printed);
	}

	/** @test */
	public function user_can_create_class_reference_using_reference_factory()
	{
		$class = Reference::classReference('Test\Unit\ReferenceTest');


		$printed = $class->print();


		$this->assertStringContainsString('Test\Unit\ReferenceTest::class', $printed);
	}

	/** @test */
	public function user_can_create_class_reference_using_importable()
	{
		$class = Reference::classReference(new Importable('Test\Unit\ReferenceTest'));


		$printed = $class->print();


		$this->assertStringContainsString('ReferenceTest::class', $printed);

		$delegatedImports = $class->getDelegatedImports();
		$this->assertCount(1, $delegatedImports);
		$this->assertEquals('Test\Unit\ReferenceTest', $delegatedImports['Test\Unit\ReferenceTest']->getName());
	}

	public function validClassReferenceNormalizationValues()
	{
		return [
			[new ClassReference('ClassName'), 'ClassName'],
			[new Importable('Some\Namespace\ClassName'), 'ClassName'],
			['SomeClass', 'SomeClass']
		];
	}

	/**
	 * @test
	 * @dataProvider validClassReferenceNormalizationValues
	 */
	public function values_can_be_normalized_to_class_reference($value, string $expectedName)
	{
		$normalized = ClassReference::normalize($value);


		$this->assertInstanceOf(ClassReference::class, $normalized);
		$this->assertEquals($expectedName, $normalized->getName());
	}

	/** @test */
	public function invalid_values_cannot_be_normalized_to_class_reference()
	{
		$invalidValue = 15;


		$this->expectException(\InvalidArgumentException::class);


		ClassReference::normalize($invalidValue);
	}

	/** @test */
	public function user_can_create_array_key_reference_using_direct_instructor()
	{
		$arrayKeyReference = new ArrayKeyReference(Variable::name('test'), Value::string('test-key'));


		$printed = $arrayKeyReference->print();


		$this->assertStringContainsString("\$test['test-key'];", $printed);
	}

	/** @test */
	public function user_can_create_array_key_reference_using_factory_method()
	{
		$arrayKeyReference = Reference::arrayFetch(Block::invokeFunction('getArray'), 'test');


		$printed = $arrayKeyReference->print();


		$this->assertStringContainsString("getArray()['test'];", $printed);
	}

	/**
	 * @test
	 * @dataProvider arrayablesDataProvider
	 */
	public function user_can_create_array_key_reference_using_any_arrayable(Arrayable $array, string $printedArray)
	{
		$arrayKeyReference = Reference::arrayFetch($array, 'test');


		$printed = $arrayKeyReference->print();


		$this->assertStringContainsString("{$printedArray}['test'];", $printed);
	}

	/**
	 * @test
	 * @dataProvider assignableValuesDataProvider
	 */
	public function user_can_create_array_key_reference_using_any_assignable_value($key, string $printedKey)
	{
		$arrayKeyReference = Reference::arrayFetch(Reference::variable('test'), $key);


		$printed = $arrayKeyReference->print();


		$this->assertStringContainsString("\$test[{$printedKey}];", $printed);
	}

	/** @test */
	public function user_can_create_nested_array_key_reference()
	{
		$arrayKeyReference = Reference::arrayFetch(Reference::variable('test'), 'first-level');


		$arrayKeyReference->nest('second-level', Block::invokeFunction('getThirdLevel'))->nest(Block::invokeStaticMethod('ArrayClass', 'getFourthLevel'));
		$printed = $arrayKeyReference->print();


		$this->assertStringContainsString("\$test['first-level']['second-level'][getThirdLevel()][ArrayClass::getFourthLevel()];", $printed);
	}
}