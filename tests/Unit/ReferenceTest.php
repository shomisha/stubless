<?php

namespace Shomisha\Stubless\Test\Unit;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\ObjectProperty;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\SelfReference;
use Shomisha\Stubless\References\StaticProperty;
use Shomisha\Stubless\References\StaticReference;
use Shomisha\Stubless\References\This;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Utilities\Importable;

class ReferenceTest extends TestCase
{
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

	/** @test */
	public function user_can_create_static_property_reference_using_direct_constructor()
	{
		$staticProperty = new StaticProperty('App\Models\User', 'totalCount');


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
		$staticProperty = new StaticProperty(new Importable('App\Models\Animals'), 'uniqueSpecies');


		$printed = $staticProperty->print();


		$this->assertStringContainsString('Animals::$uniqueSpecies', $printed);
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
}