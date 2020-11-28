<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Templates\ClassMethod;
use Shomisha\Stubless\Templates\ClassTemplate;
use Shomisha\Stubless\Utilities\Importable;

class ClassMethodTest extends TestCase
{
	/** @test */
	public function users_can_create_methods_with_all_characteristics()
	{
		$method = ClassMethod::name('doSomething');

		$method->makeFinal();

		$method->makeProtected();

		$method->addArgument(Argument::name('firstParameter'));
		$method->addArgument(Argument::name('secondParameter')->type('string'));

		$method->return('bool');


		$printed = $method->print();


		$this->assertStringContainsString("protected final function doSomething(\$firstParameter, string \$secondParameter) : bool\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_final_methods()
	{
		$method = ClassMethod::name('finalMethod')->makeFinal();


		$printed = $method->print();


		$this->assertStringContainsString("public final function finalMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_final_methods_using_fluent_alias()
	{
		$method = ClassMethod::name('test')->final(true);


		$isFinal = $method->isFinal();


		$this->assertTrue($isFinal);
	}

	/** @test */
	public function users_can_check_if_method_is_final()
	{
		$method = ClassMethod::name('test');


		$isFinal = $method->isFinal();


		$this->assertFalse($isFinal);
	}

	/** @test */
	public function user_can_check_if_method_is_final_using_fluent_alias()
	{
		$method = ClassMethod::name('test')->makeFinal();


		$isFinal = $method->final();


		$this->assertTrue($isFinal);
	}

	/** @test */
	public function users_can_create_abstract_methods()
	{
		$method = ClassMethod::name('abstractMethod')->makeAbstract();


		$printed = $method->print();


		$this->assertStringContainsString("public abstract function abstractMethod();", $printed);
	}

	/** @test */
	public function users_can_create_abstract_methods_using_fluent_alias()
	{
		$method = ClassMethod::name('test')->abstract(true);


		$isAbstract = $method->isAbstract();


		$this->assertTrue($isAbstract);
	}

	/** @test */
	public function users_can_check_if_method_is_abstract()
	{
		$method = ClassMethod::name('test');


		$isAbstract = $method->isAbstract();


		$this->assertFalse($isAbstract);
	}

	/** @test */
	public function user_can_check_if_method_is_abstract_using_fluent_alias()
	{
		$method = ClassMethod::name('test')->makeAbstract(true);


		$isAbstract = $method->abstract();


		$this->assertTrue($isAbstract);
	}

	/** @test */
	public function users_can_create_public_methods()
	{
		$method = ClassMethod::name('publicMethod')->makePublic();


		$printed = $method->print();


		$this->assertStringContainsString("public function publicMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_protected_methods()
	{
		$method = ClassMethod::name('protectedMethod')->makeProtected();


		$printed = $method->print();


		$this->assertStringContainsString("protected function protectedMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_private_methods()
	{
		$method = ClassMethod::name('privateMethod')->makePrivate();


		$printed = $method->print();


		$this->assertStringContainsString("private function privateMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_methods_with_importable_arguments()
	{
		$method = ClassMethod::name('methodWithImportableArgument');
		$method->addArgument(
			Argument::name('object')->type(new Importable(\App\Models\User::class))
		);

		$class = ClassTemplate::name('TestClass')->addMethod($method)->setNamespace('Test');


		$printed = $class->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("public function methodWithImportableArgument(User \$object)", $printed);
	}

	/** @test */
	public function users_can_create_methods_without_arguments()
	{
		$method = ClassMethod::name('methodWithoutArguments');


		$printed = $method->print();


		$this->assertStringContainsString("public function methodWithoutArguments()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_override_all_method_arguments()
	{
		$method = ClassMethod::name('test');

		$method->addArgument(Argument::name('test'))->addArgument(Argument::name('anotherTest'));


		$method->withArguments([Argument::name('iWillSurvive')]);
		$printed = $method->print();


		$this->assertStringContainsString("public function test(\$iWillSurvive)\n{\n}", $printed);
	}

	/** @test */
	public function users_cannot_override_all_method_arguments_using_invalid_array()
	{
		$method = ClassMethod::name('test');

		$this->expectException(\InvalidArgumentException::class);


		$method->withArguments([
			Argument::name('test'),
			123,
			'not a valid argument',
		]);
	}

	/** @test */
	public function users_can_remove_method_arguments_by_name()
	{
		$method = ClassMethod::name('test');

		$method->addArgument(Argument::name('test'))->addArgument(Argument::name('anotherTest'));


		$method->removeArgument('anotherTest');
		$printed = $method->print();


		$this->assertStringContainsString("public function test(\$test)\n{\n}", $printed);
	}

	/** @test */
	public function users_can_set_arguments_using_fluent_alias()
	{
		$method = ClassMethod::name('doSomething');


		$method->arguments([
			Argument::name('firstArgument')->type('string'),
			Argument::name('secondArgument')->type('bool'),
		]);
		$printed = $method->print();


		$this->assertStringContainsString('function doSomething(string $firstArgument, bool $secondArgument)', $printed);
	}

	/** @test */
	public function users_can_get_all_arguments()
	{
		$method = ClassMethod::name('doSomething');

		$method->arguments([
			Argument::name('firstArgument')->type('string'),
			Argument::name('secondArgument')->type('bool'),
		]);


		$arguments = $method->getArguments();


		$this->assertIsArray($arguments);
		$this->assertCount(2, $arguments);

		$this->assertEquals('firstArgument', $arguments['firstArgument']->getName());
		$this->assertEquals('string', $arguments['firstArgument']->getType());

		$this->assertEquals('secondArgument', $arguments['secondArgument']->getName());
		$this->assertEquals('bool', $arguments['secondArgument']->getType());
	}

	/** @test */
	public function users_can_get_all_arguments_using_fluent_alias()
	{
		$method = ClassMethod::name('doSomething');

		$method->arguments([
			Argument::name('firstArgument')->type('string'),
			Argument::name('secondArgument')->type('bool'),
		]);


		$arguments = $method->arguments();


		$this->assertIsArray($arguments);
		$this->assertCount(2, $arguments);

		$this->assertEquals('firstArgument', $arguments['firstArgument']->getName());
		$this->assertEquals('string', $arguments['firstArgument']->getType());

		$this->assertEquals('secondArgument', $arguments['secondArgument']->getName());
		$this->assertEquals('bool', $arguments['secondArgument']->getType());
	}

	/** @test */
	public function users_can_create_methods_with_importable_return_types()
	{
		$method = ClassMethod::name('methodWithImportableReturnType');

		$method->return(new Importable(\App\Models\Concert::class, 'ConcertModel'));

		$class = ClassTemplate::name('TestClass')->setNamespace('Test');
		$class->addMethod($method);


		$printed = $class->print();


		$this->assertStringContainsString('use App\Models\Concert as ConcertModel;', $printed);
		$this->assertStringContainsString("public function methodWithImportableReturnType() : ConcertModel\n", $printed);
	}

	/** @test */
	public function users_can_create_methods_without_return_types()
	{
		$method = ClassMethod::name('methodWithoutReturnType');


		$printed = $method->print();


		$this->assertStringContainsString("public function methodWithoutReturnType()\n{\n}", $printed);
	}

	/** @test */
	public function user_can_get_method_return_type()
	{
		$method = ClassMethod::name('doSomething')->setReturnType('bool');


		$returnType = $method->getReturnType();


		$this->assertEquals('bool', $returnType);
	}

	/** @test */
	public function user_can_get_method_return_type_using_fluent_alias()
	{
		$method = ClassMethod::name('doSomething')->setReturnType('float');


		$returnType = $method->return();


		$this->assertEquals('float', $returnType);
	}

	/** @test */
	public function user_can_make_methods_static()
	{
		$method = ClassMethod::name('staticMethodTest');


		$method->makeStatic();
		$printed = $method->print();


		$this->assertStringContainsString('public static function staticMethodTest()', $printed);
	}

	/** @test */
	public function user_can_make_methods_static_using_the_fluent_alias()
	{
		$method = ClassMethod::name('staticMethodTest');


		$method->static(true);
		$printed = $method->print();


		$this->assertStringContainsString('public static function staticMethodTest()', $printed);
	}

	/**
	 * @test
	 * @testWith [true]
	 * 			 [false]
	 */
	public function user_can_check_if_methods_are_static($shouldBeStatic)
	{
		$method = ClassMethod::name('staticMethod')->makeStatic($shouldBeStatic);


		$isStatic = $method->isStatic();


		$this->assertEquals($shouldBeStatic, $isStatic);
	}

	/**
	 * @test
	 * @testWith [true]
	 *			 [false]
	 */
	public function user_can_check_if_methods_are_static_using_the_fluent_alias($shouldBeStatic)
	{
		$method = ClassMethod::name('staticMethod')->makeStatic($shouldBeStatic);


		$isStatic = $method->static();


		$this->assertEquals($shouldBeStatic, $isStatic);
	}
}