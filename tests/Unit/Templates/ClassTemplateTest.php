<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Templates\ClassConstant;
use Shomisha\Stubless\Templates\ClassMethod;
use Shomisha\Stubless\Templates\ClassProperty;
use Shomisha\Stubless\Templates\ClassTemplate;
use Shomisha\Stubless\Templates\UseStatement;
use Shomisha\Stubless\Utilities\Importable;

class ClassTemplateTest extends TestCase
{
	/** @test */
	public function users_can_create_classes_with_all_characteristics()
	{
		$class = ClassTemplate::name('Human')
							  ->setNamespace('\App\World')
							  ->extends(new Importable(\App\Species\HomoSapiens::class))
							  ->implements([new Importable(\App\Contracts\LivingBeing::class)])
							  ->uses([new Importable(\App\Concerns\Consciousness::class), new Importable(\App\Concerns\Intelligence::class)]);

		$class->constants([
			ClassConstant::name('GENDER_MALE')->value('M'),
			ClassConstant::name('GENDER_FEMALE')->value('F'),
		]);

		$class->withProperties([
			ClassProperty::name('gender')->type(new Importable(\App\Characteristics\Gender::class)),
			ClassProperty::name('age')->type('int'),
			ClassProperty::name('height')->makePrivate(),
		]);

		$class->withMethods([
			ClassMethod::name('sleep')->return('void'),
			ClassMethod::name('wake')->return('void'),
			ClassMethod::name('speak')->return('array'),
			ClassMethod::name('eat')->withArguments([
				Argument::name('food')->type(new Importable(\App\Utilities\Food::class)),
			])->return('void'),
		]);


		$printed = $class->print();


		// die($printed);
		$this->assertStringContainsString('namespace \App\World;', $printed);

		$this->assertStringContainsString('use App\Contracts\LivingBeing;', $printed);
		$this->assertStringContainsString('use App\Concerns\Consciousness;', $printed);
		$this->assertStringContainsString('use App\Concerns\Intelligence;', $printed);
		$this->assertStringContainsString('use App\Characteristics\Gender;', $printed);
		$this->assertStringContainsString('use App\Utilities\Food;', $printed);

		$this->assertStringContainsString('class Human extends HomoSapiens implements LivingBeing', $printed);

		$this->assertStringContainsString('use Consciousness, Intelligence;', $printed);

		$this->assertStringContainsString("public const GENDER_MALE = 'M';", $printed);
		$this->assertStringContainsString("public const GENDER_FEMALE = 'F';", $printed);

		$this->assertStringContainsString("public Gender \$gender;", $printed);
		$this->assertStringContainsString("public int \$age;", $printed);
		$this->assertStringContainsString("private \$height;", $printed);

		$this->assertStringContainsString("public function sleep() : void", $printed);
		$this->assertStringContainsString("public function wake() : void", $printed);
		$this->assertStringContainsString("public function speak() : array", $printed);
		$this->assertStringContainsString("public function eat(Food \$food) : void", $printed);
	}

	/** @test */
	public function users_can_create_classes_with_namespace()
	{
		$class = ClassTemplate::name('TestClass');

		$class->setNamespace('App\Test\Classes');


		$printed = $class->print();


		$this->assertStringContainsString('namespace App\Test\Classes;', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_check_if_class_has_a_namespace(bool $shouldHaveNamespace)
	{
		$class = ClassTemplate::name('TestClass');

		if ($shouldHaveNamespace) {
			$class->setNamespace('App\Test');
		}


		$actuallyHasNamespace = $class->hasNamespace();


		$this->assertEquals($shouldHaveNamespace, $actuallyHasNamespace);
	}

	/** @test */
	public function users_can_get_class_namespace()
	{
		$class = ClassTemplate::name('TestClass');

		$class->setNamespace('App\Test');


		$setNamespace = $class->getNamespace();


		$this->assertEquals('App\Test', $setNamespace);
	}

	/** @test */
	public function users_can_add_imports_to_class()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('Test');

		$class->addImport(new UseStatement('App\Test\SomeClass'));


		$printed = $class->print();


		$this->assertStringContainsString('use App\Test\SomeClass;', $printed);
	}

	/** @test */
	public function users_can_add_imports_even_if_a_class_has_no_namespace()
	{
		$class = ClassTemplate::name('TestClass');

		$class->addImport(UseStatement::name('App\Models\User'));
		$class->extends(new Importable('Illuminate\Routing\Controller'));


		$printed = $class->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('use Illuminate\Routing\Controller;', $printed);
		$this->assertStringNotContainsString('namespace', $printed);
	}

	/** @test */
	public function users_can_get_imports_from_class()
	{
		$class = ClassTemplate::name('TestClass')->addImport(
			new UseStatement('App\Test\SomeClass', 'ImportedClass'),
		);


		$imports = $class->getImports();


		$this->assertIsArray($imports);

		$import = $imports['App\Test\SomeClass'];
		$this->assertEquals('App\Test\SomeClass', $import->getName());
		$this->assertEquals('ImportedClass', $import->getAs());
	}

	/** @test */
	public function users_can_get_imports_from_class_using_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->withImports([
			UseStatement::name('Test\AnotherTestClass'),
			UseStatement::name('Test\DoesSomething')->setAs('DoesSomethingTrait'),
		]);


		$imports = $class->imports();


		$this->assertIsArray($imports);
		$this->assertCount(2, $imports);

		$this->assertEquals('Test\AnotherTestClass', $imports['Test\AnotherTestClass']->getName());
		$this->assertEquals('Test\DoesSomething', $imports['Test\DoesSomething']->getName());
		$this->assertEquals('Test\DoesSomething', $imports['Test\DoesSomething']->getName());
		$this->assertEquals('DoesSomethingTrait', $imports['Test\DoesSomething']->getAs());
	}

	/** @test */
	public function users_can_add_importables_to_class()
	{
		$class = ClassTemplate::name('TestClass');

		$importable = new Importable('App\Test\SomeClass');


		$class->addImportable($importable);
		$imports = $class->getImports();


		$import = $imports[0];
		$this->assertInstanceOf(UseStatement::class, $import);
		$this->assertEquals('App\Test\SomeClass', $import->getName());
		$this->assertNull($import->getAs());
	}

	/** @test */
	public function users_can_remove_imports_from_class()
	{
		$class = ClassTemplate::name('TestClass');

		$class->addImport(UseStatement::name('App\Test\SomeClass'));


		$class->removeImport('App\Test\SomeClass');
		$imports = $class->getImports();


		$this->assertEmpty($imports);
	}

	/** @test */
	public function users_can_override_imports_on_class()
	{
		$class = ClassTemplate::name('TestClass')->addImport(new UseStatement('App\Test\SomeClass'));


		$class->withImports([
			new UseStatement('App\Test\AnotherClass'),
			new UseStatement('App\Test\YetAnotherClass'),
		]);
		$imports = $class->getImports();


		$this->assertCount(2, $imports);
		$this->assertEquals('App\Test\AnotherClass', $imports['App\Test\AnotherClass']->getName());
		$this->assertEquals('App\Test\YetAnotherClass', $imports['App\Test\YetAnotherClass']->getName());
	}

	/** @test */
	public function users_can_override_imports_on_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->addImport(new UseStatement('App\Test\SomeClass'));


		$class->imports([
			new UseStatement('App\Test\AnotherClass'),
			new UseStatement('App\Test\YetAnotherClass'),
		]);
		$imports = $class->getImports();


		$this->assertCount(2, $imports);
		$this->assertEquals('App\Test\AnotherClass', $imports['App\Test\AnotherClass']->getName());
		$this->assertEquals('App\Test\YetAnotherClass', $imports['App\Test\YetAnotherClass']->getName());
	}

	/** @test */
	public function users_cannot_override_imports_using_an_invalid_array()
	{
		$class = ClassTemplate::name('TestClass')->addImport(new UseStatement('App\Test\SomeClass'));


		$this->expectException(\InvalidArgumentException::class);
		$class->withImports([
			'Not a use statement',
			12
		]);
	}

	/** @test */
	public function class_will_include_delegated_imports_in_final_print()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('App\Test');

		$class->addProperty(
			ClassProperty::name('test')->type(new Importable('App\Test\TestType')),
		);

		$class->addMethod(
			ClassMethod::name('doSomething')->return(new Importable('App\Test\ReturnType', 'MethodReturnType'))
		);


		$printed = $class->print();


		$this->assertStringContainsString('use App\Test\TestType;', $printed);
		$this->assertStringContainsString('use App\Test\ReturnType as MethodReturnType;', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_create_abstract_classes(bool $shouldBeAbstract)
	{
		$class = ClassTemplate::name('PotentiallyAbstractClass');


		$class->makeAbstract($shouldBeAbstract);
		$printed = $class->print();


		if ($shouldBeAbstract) {
			return $this->assertStringContainsString('abstract class PotentiallyAbstractClass', $printed);
		}

		$this->assertStringNotContainsString('abstract', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_make_the_class_abstract_using_the_fluent_alias(bool $shouldBeAbstract)
	{
		$class = ClassTemplate::name('PotentiallyAbstractClass');


		$class->abstract($shouldBeAbstract);
		$printed = $class->print();


		if ($shouldBeAbstract) {
			return $this->assertStringContainsString('abstract class PotentiallyAbstractClass', $printed);
		}

		$this->assertStringNotContainsString('abstract', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 * 			 [false]
	 */
	public function users_can_check_if_a_class_is_abstract(bool $shouldBeAbstract)
	{
		$class = ClassTemplate::name('PotentiallyAbstractClass')->abstract($shouldBeAbstract);


		$actuallyIsAbstract = $class->isAbstract();


		$this->assertEquals($actuallyIsAbstract, $shouldBeAbstract);
	}

	/**
	 * @test
	 * @testWith [true]
	 * 			 [false]
	 */
	public function users_can_check_if_a_class_is_abstract_using_the_fluent_alias(bool $shouldBeAbstract)
	{
		$class = ClassTemplate::name('PotentiallyAbstractClass')->makeAbstract($shouldBeAbstract);


		$isActuallyAbstract = $class->abstract();


		$this->assertEquals($shouldBeAbstract, $isActuallyAbstract);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_create_final_classes(bool $shouldBeFinal)
	{
		$class = ClassTemplate::name('PotentiallyFinalClass');


		$class->makeFinal($shouldBeFinal);
		$printed = $class->print();


		if ($shouldBeFinal) {
			return $this->assertStringContainsString('final class PotentiallyFinalClass', $printed);
		}

		$this->assertStringNotContainsString('final', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_make_the_class_final_using_the_fluent_alias(bool $shouldBeFinal)
	{
		$class = ClassTemplate::name('PotentiallyFinalClass');


		$class->final($shouldBeFinal);
		$printed = $class->print();


		if ($shouldBeFinal) {
			return $this->assertStringContainsString('final class PotentiallyFinalClass', $printed);
		}

		$this->assertStringNotContainsString('final', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_check_if_a_class_is_final(bool $shouldBeFinal)
	{
		$class = ClassTemplate::name('PotentiallyFinalClass')->makeFinal($shouldBeFinal);


		$isActuallyFinal = $class->isFinal();


		$this->assertEquals($shouldBeFinal, $isActuallyFinal);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function users_can_check_if_a_class_is_final_using_the_fluent_alias(bool $shouldBeFinal)
	{
		$class = ClassTemplate::name('PotentiallyFinalClass')->makeFinal($shouldBeFinal);


		$isActuallyFinal = $class->final();


		$this->assertEquals($shouldBeFinal, $isActuallyFinal);
	}

	/** @test */
	public function users_can_create_a_class_using_the_named_constructor()
	{
		$name = 'TestClass';


		$class = ClassTemplate::name($name);


		$this->assertInstanceOf(ClassTemplate::class, $class);
		$this->assertEquals($name, $class->getName());
	}

	/** @test */
	public function users_can_create_a_class_using_its_regular_constructor()
	{
		$name = 'TestClass';
		$extends = 'SomeParentClass';


		$class = new ClassTemplate('TestClass', 'ParentClass');
		$printed = $class->print();


		$this->assertStringContainsString('class TestClass extends ParentClass', $printed);
	}

	/** @test */
	public function users_can_get_a_classes_name()
	{
		$class = ClassTemplate::name('TestClass');


		$className = $class->getName();


		$this->assertEquals('TestClass', $className);
	}

	/** @test */
	public function users_can_set_a_classes_name()
	{
		$class = ClassTemplate::name('');


		$class->setName('ClassName');


		$this->assertEquals('ClassName', $class->getName());
	}

	/** @test */
	public function users_can_set_a_parent_to_the_class()
	{
		$class = ClassTemplate::name('TestClass');


		$class->setExtends('ParentClass');
		$printed = $class->print();


		$this->assertStringContainsString('class TestClass extends ParentClass', $printed);
	}

	/** @test */
	public function users_can_set_an_importable_as_a_parent_to_the_class()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('Test');

		$class->setExtends(new Importable('App\Test\ParentClass'));


		$printed = $class->print();


		$this->assertStringContainsString('use App\Test\ParentClass;', $printed);
		$this->assertStringContainsString('class TestClass extends ParentClass', $printed);
	}

	/** @test */
	public function users_can_set_a_parent_to_the_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass');


		$class->extends('ParentClass');
		$printed = $class->print();


		$this->assertStringContainsString('class TestClass extends ParentClass', $printed);
	}

	/** @test */
	public function users_can_get_the_parent_of_a_class()
	{
		$class = ClassTemplate::name('TestClass')->extends('ParentClass');


		$parent = $class->getExtends();


		$this->assertEquals('ParentClass', $parent);
	}

	/** @test */
	public function users_can_get_the_parent_of_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->extends('ParentClass');


		$parent = $class->extends();


		$this->assertEquals('ParentClass', $parent);
	}

	/** @test */
	public function users_can_add_interfaces_to_a_class()
	{
		$class = ClassTemplate::name('TestClass');


		$class->withInterfaces([
			'App\Contracts\DoesSomething',
			'App\Contracts\DoesSomethingElse',
		]);
		$printed = $class->print();


		$this->assertStringContainsString(
			'class TestClass implements App\Contracts\DoesSomething, App\Contracts\DoesSomethingElse',
			$printed
		);
	}

	/** @test */
	public function users_can_remove_interfaces_from_a_class()
	{
		$class = ClassTemplate::name('TestClass')->withInterfaces([
			'App\Contracts\DoesSomethingImportant',
			'App\Contracts\NotSoImportant'
		]);

		$this->assertCount(2, $class->getInterfaces());


		$class->removeInterface('App\Contracts\NotSoImportant');


		$newInterfaces = $class->getInterfaces();
		$this->assertCount(1, $newInterfaces);

		$this->assertEquals('App\Contracts\DoesSomethingImportant', $newInterfaces['App\Contracts\DoesSomethingImportant']);
	}

	/** @test */
	public function users_can_add_importables_as_interfaces_to_a_class()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('Test');


		$class->withInterfaces([
			new Importable('App\Contracts\DoesSomething'),
			new Importable('App\OtherContracts\DoesSomething', 'DoesSomethingElse'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('use App\Contracts\DoesSomething;', $printed);
		$this->assertStringContainsString('use App\OtherContracts\DoesSomething as DoesSomethingElse;', $printed);
	}

	/** @test */
	public function users_can_add_interfaces_to_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('Test');


		$class->implements([
			'App\Contracts\DoesSomething',
			new Importable('App\Contracts\DoesSomethingElse'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('use App\Contracts\DoesSomethingElse;', $printed);
		$this->assertStringContainsString('class TestClass implements App\Contracts\DoesSomething, DoesSomethingElse', $printed);
	}

	/** @test */
	public function users_can_get_interfaces_from_a_class()
	{
		$class = ClassTemplate::name('TestClass');

		$class->withInterfaces([
			'App\Contracts\DoesSomething',
			'App\Contracts\DoesNothing',
		]);


		$interfaces = $class->getInterfaces();


		$this->assertEquals([
			'App\Contracts\DoesSomething' => 'App\Contracts\DoesSomething',
			'App\Contracts\DoesNothing' => 'App\Contracts\DoesNothing',
		], $interfaces);
	}

	/** @test */
	public function users_can_get_interfaces_from_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass');

		$class->withInterfaces([
			'App\Contracts\DoesSomething',
			'App\Contracts\DoesNothing',
		]);


		$interfaces = $class->implements();


		$this->assertEquals([
			'App\Contracts\DoesSomething' => 'App\Contracts\DoesSomething',
			'App\Contracts\DoesNothing' => 'App\Contracts\DoesNothing',
		], $interfaces);
	}

	/** @test */
	public function users_can_add_traits_to_a_class()
	{
		$class = ClassTemplate::name('TestClass');


		$class->withTraits([
			'App\Concerns\HelpsWithOneThing',
			'App\Concerns\HelpsWithAnotherThing',
		]);
		$printed = $class->print();


		$this->assertStringContainsString("class TestClass\n{\n    use App\Concerns\HelpsWithOneThing, App\Concerns\HelpsWithAnotherThing;", $printed);
	}



	/** @test */
	public function users_can_remove_traits_from_a_class()
	{
		$class = ClassTemplate::name('TestClass')->withTraits([
			'App\Concerns\DoesSomethingImportant',
			'App\Concerns\NotSoImportant'
		]);

		$this->assertCount(2, $class->getTraits());


		$class->removeTrait('App\Concerns\NotSoImportant');


		$newTraits = $class->getTraits();
		$this->assertCount(1, $newTraits);

		$this->assertEquals('App\Concerns\DoesSomethingImportant', $newTraits['App\Concerns\DoesSomethingImportant']);
	}

	/** @test */
	public function users_can_add_importables_as_traits_to_a_class()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('Test');


		$class->withTraits([
			new Importable('App\Concerns\DoesSomething'),
			new Importable('App\Concerns\Helpers', 'HelpersTrait'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('use App\Concerns\DoesSomething;', $printed);
		$this->assertStringContainsString('use App\Concerns\Helpers as HelpersTrait', $printed);
		$this->assertStringContainsString("class TestClass\n{\n    use DoesSomething, HelpersTrait;", $printed);
	}

	/** @test */
	public function users_can_add_traits_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->setNamespace('Test');


		$class->uses([
			'App\Concerns\DoesSomething',
			new Importable('App\Concerns\Helpers', 'HelpersTrait'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('use App\Concerns\Helpers as HelpersTrait', $printed);
		$this->assertStringContainsString("class TestClass\n{\n    use App\Concerns\DoesSomething, HelpersTrait;", $printed);
	}

	/** @test */
	public function users_can_get_traits_from_a_class()
	{
		$class = ClassTemplate::name('TestClass');

		$class->withTraits([
			'App\Concerns\Helpers',
			'App\Concerns\Calculations',
		]);


		$traits = $class->getTraits();


		$this->assertEquals([
			'App\Concerns\Helpers' => 'App\Concerns\Helpers',
			'App\Concerns\Calculations' => 'App\Concerns\Calculations',
		], $traits);
	}

	/** @test */
	public function users_can_get_traits_from_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->withTraits([
			'App\Concerns\Helpers',
			'App\Concerns\Calculations',
		]);


		$traits = $class->uses();


		$this->assertEquals([
			'App\Concerns\Helpers' => 'App\Concerns\Helpers',
			'App\Concerns\Calculations' => 'App\Concerns\Calculations',
		], $traits);
	}

	/** @test */
	public function users_can_add_constants_to_a_class()
	{
		$class = ClassTemplate::name('Person');


		$class->withConstants([
			ClassConstant::name('MIN_AGE')->value(18),
			ClassConstant::name('GENDER_FEMALE')->value('f'),
			ClassConstant::name('GENDER_MALE')->value('m'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('const MIN_AGE = 18;', $printed);
		$this->assertStringContainsString("const GENDER_FEMALE = 'f';", $printed);
		$this->assertStringContainsString("const GENDER_MALE = 'm';", $printed);
	}

	/** @test */
	public function users_can_add_constants_to_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('Person');


		$class->constants([
			ClassConstant::name('GENDER_MALE')->value('m'),
			ClassConstant::name('GENDER_FEMALE')->value('f'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString("const GENDER_MALE = 'm'", $printed);
		$this->assertStringContainsString("const GENDER_FEMALE = 'f'", $printed);
	}

	/** @test */
	public function users_can_get_constants_from_a_class()
	{
		$class = ClassTemplate::name('Person')->withConstants([
			ClassConstant::name('GENDER_MALE')->value('m'),
			ClassConstant::name('GENDER_FEMALE')->value('f'),
		]);


		$constants = $class->getConstants();


		$this->assertCount(2, $constants);

		$this->assertEquals('GENDER_MALE', $constants['GENDER_MALE']->getName());
		$this->assertEquals('m', $constants['GENDER_MALE']->getValue());

		$this->assertEquals('GENDER_FEMALE', $constants['GENDER_FEMALE']->getName());
		$this->assertEquals('f', $constants['GENDER_FEMALE']->getValue());
	}

	/** @test */
	public function users_can_get_constants_from_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('Person')->withConstants([
			ClassConstant::name('GENDER_MALE')->value('m'),
			ClassConstant::name('GENDER_FEMALE')->value('f'),
		]);


		$constants = $class->constants();


		$this->assertCount(2, $constants);

		$this->assertEquals('GENDER_MALE', $constants['GENDER_MALE']->getName());
		$this->assertEquals('m', $constants['GENDER_MALE']->getValue());

		$this->assertEquals('GENDER_FEMALE', $constants['GENDER_FEMALE']->getName());
		$this->assertEquals('f', $constants['GENDER_FEMALE']->getValue());
	}

	/** @test */
	public function users_can_add_a_single_constant_to_a_class()
	{
		$class = ClassTemplate::name('Person');


		$class->addConstant(
			ClassConstant::name('MIN_AGE')->value(21),
		);
		$printed = $class->print();


		$this->assertStringContainsString('const MIN_AGE = 21;', $printed);
	}

	/** @test */
	public function users_can_remove_a_single_constant_from_a_class()
	{
		$class = ClassTemplate::name('Person')->withConstants([
			ClassConstant::name('MIN_AGE')->value(18),
			ClassConstant::name('GENDER_FEMALE')->value('f'),
			ClassConstant::name('GENDER_MALE')->value('m'),
		]);

		$this->assertCount(3, $class->getConstants());


		$class->removeConstant('MIN_AGE');
		$constants = $class->getConstants();


		$this->assertCount(2, $constants);
		$this->assertEquals([
			'GENDER_FEMALE', 'GENDER_MALE'
		], array_keys($constants));
	}

	/** @test */
	public function users_can_add_properties_to_a_class()
	{
		$class = ClassTemplate::name('TestClass');


		$class->addProperty(
			ClassProperty::name('age')->type('int')->value(18)->makeProtected()
		);
		$printed = $class->print();


		$this->assertStringContainsString('protected int $age = 18;', $printed);
	}

	/** @test */
	public function users_can_remove_properties_from_a_class_by_name()
	{
		$class = ClassTemplate::name('TestClass')->addProperty(
			ClassProperty::name('age')->type('int')->makeProtected()
		);


		$class->removeProperty('age');
		$printed = $class->print();


		$this->assertStringNotContainsString('protected int $age;', $printed);
	}

	/** @test */
	public function users_can_override_all_properties()
	{
		$class = ClassTemplate::name('TestClass')->addProperty(
			ClassProperty::name('age')->makePrivate()
		);


		$class->withProperties([
			ClassProperty::name('gender')->makePublic(),
			ClassProperty::name('first_name')->makePublic(),
			ClassProperty::name('last_name')->makeProtected()
		]);
		$printed = $class->print();


		$this->assertStringContainsString('public $gender;', $printed);
		$this->assertStringContainsString('public $first_name;', $printed);
		$this->assertStringContainsString('protected $last_name;', $printed);
		$this->assertStringNotContainsString('private $age;', $printed);
	}

	/** @test */
	public function users_can_override_all_properties_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->addProperty(
			ClassProperty::name('age')->makePrivate()
		);


		$class->properties([
			ClassProperty::name('gender')->makePublic(),
			ClassProperty::name('first_name')->makePublic(),
			ClassProperty::name('last_name')->makeProtected()
		]);
		$printed = $class->print();


		$this->assertStringContainsString('public $gender;', $printed);
		$this->assertStringContainsString('public $first_name;', $printed);
		$this->assertStringContainsString('protected $last_name;', $printed);
		$this->assertStringNotContainsString('private $age;', $printed);
	}

	/** @test */
	public function users_cannot_override_properties_using_an_invalid_array()
	{
		$class = ClassTemplate::name('TestClass')->addProperty(
			ClassProperty::name('age')->makePrivate()
		);


		$this->expectException(\InvalidArgumentException::class);


		$class->withProperties([
			ClassProperty::name('test'),
			'i am not a class property',
		]);
	}

	/** @test */
	public function users_can_get_properties_from_a_class()
	{
		$class = ClassTemplate::name('TestClass')->withProperties([
			ClassProperty::name('age')->type('int'),
			ClassProperty::name('gender')->type('string'),
		]);


		$properties = $class->getProperties();


		$this->assertIsArray($properties);
		$this->assertCount(2, $properties);

		$this->assertEquals('age', $properties['age']->getName());
		$this->assertEquals('int', $properties['age']->getType());

		$this->assertEquals('gender', $properties['gender']->getName());
		$this->assertEquals('string', $properties['gender']->getType());
	}

	/** @test */
	public function users_can_get_properties_from_a_class_using_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->withProperties([
			ClassProperty::name('age')->type('int'),
			ClassProperty::name('gender')->type('string'),
		]);


		$properties = $class->properties();


		$this->assertIsArray($properties);
		$this->assertCount(2, $properties);

		$this->assertEquals('age', $properties['age']->getName());
		$this->assertEquals('int', $properties['age']->getType());

		$this->assertEquals('gender', $properties['gender']->getName());
		$this->assertEquals('string', $properties['gender']->getType());
	}

	/** @test */
	public function users_can_add_methods_to_a_class()
	{
		$class = ClassTemplate::name('TestClass');


		$class->addMethod(
			ClassMethod::name('doSomething')->makeProtected()->return('string')
		);
		$printed = $class->print();


		$this->assertStringContainsString("protected function doSomething() : string", $printed);
	}

	/** @test */
	public function users_can_remove_methods_from_a_class_by_name()
	{
		$class = ClassTemplate::name('TestClass')->addMethod(
			ClassMethod::name('doSomething')
		);


		$class->removeMethod('doSomething');
		$printed = $class->print();


		$this->assertStringNotContainsString('function doSomething()', $printed);
	}

	/** @test */
	public function users_can_override_all_methods_on_a_class()
	{
		$class = ClassTemplate::name('TestClass')->addMethod(
			ClassMethod::name('doSomething')
		);


		$class->withMethods([
			ClassMethod::name('dontDoIt')->makePublic(),
			ClassMethod::name('doSomethingElse')->makeProtected()->return('bool'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('public function dontDoIt()', $printed);
		$this->assertStringContainsString('protected function doSomethingElse() : bool', $printed);
		$this->assertStringNotContainsString('function doSomething()', $printed);
	}

	/** @test */
	public function users_can_override_all_methods_on_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->addMethod(
			ClassMethod::name('doSomething')
		);


		$class->methods([
			ClassMethod::name('dontDoIt')->makePublic(),
			ClassMethod::name('doSomethingElse')->makeProtected()->return('bool'),
		]);
		$printed = $class->print();


		$this->assertStringContainsString('public function dontDoIt()', $printed);
		$this->assertStringContainsString('protected function doSomethingElse() : bool', $printed);
		$this->assertStringNotContainsString('function doSomething()', $printed);
	}

	/** @test */
	public function users_cannot_override_methods_on_a_class_using_an_invalid_array()
	{
		$class = ClassTemplate::name('TestClass')->addMethod(
			ClassMethod::name('doSomething')
		);


		$this->expectException(\InvalidArgumentException::class);


		$class->withMethods([
			ClassMethod::name('dontDoIt')->makePublic(),
			'not a class method',
		]);
	}

	/** @test */
	public function users_can_get_methods_from_a_class()
	{
		$class = ClassTemplate::name('TestClass')->withMethods([
			ClassMethod::name('doesSomethingImportant')->return('bool'),
			ClassMethod::name('doesSomethingMoreImportant')->return('string'),
		]);


		$methods = $class->getMethods();


		$this->assertIsArray($methods);
		$this->assertCount(2, $methods);

		$this->assertEquals('doesSomethingImportant', $methods['doesSomethingImportant']->getName());
		$this->assertEquals('bool', $methods['doesSomethingImportant']->getReturnType());

		$this->assertEquals('doesSomethingMoreImportant', $methods['doesSomethingMoreImportant']->getName());
		$this->assertEquals('string', $methods['doesSomethingMoreImportant']->getReturnType());
	}

	/** @test */
	public function users_can_get_methods_from_a_class_using_the_fluent_alias()
	{
		$class = ClassTemplate::name('TestClass')->withMethods([
			ClassMethod::name('doesSomethingImportant')->return('bool'),
			ClassMethod::name('doesSomethingMoreImportant')->return('string'),
		]);


		$methods = $class->methods();


		$this->assertIsArray($methods);
		$this->assertCount(2, $methods);

		$this->assertEquals('doesSomethingImportant', $methods['doesSomethingImportant']->getName());
		$this->assertEquals('bool', $methods['doesSomethingImportant']->getReturnType());

		$this->assertEquals('doesSomethingMoreImportant', $methods['doesSomethingMoreImportant']->getName());
		$this->assertEquals('string', $methods['doesSomethingMoreImportant']->getReturnType());
	}
}