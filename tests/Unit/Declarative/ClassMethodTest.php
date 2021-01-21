<?php

namespace Shomisha\Stubless\Test\Unit\Declarative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\This;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\DeclarativeCode\Argument;
use Shomisha\Stubless\DeclarativeCode\ClassMethod;
use Shomisha\Stubless\DeclarativeCode\ClassTemplate;
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

	/** @test */
	public function user_can_set_blocks_as_method_body()
	{
		$method = ClassMethod::name('test');


		$method->setBody(Block::fromArray([
			Block::assign(Variable::name('test'), true)
		]));
		$printed = $method->print();


		$this->assertStringContainsString("public function test()\n{\n    \$test = true;\n}", $printed);
	}

	/** @test */
	public function user_can_get_method_body_block()
	{
		$method = ClassMethod::name('activate')->setBody(
			Block::fromArray([])
		);


		$body = $method->getBody();


		$this->assertInstanceOf(Block::class, $body);
	}

	public function hasBodyDataProvider()
	{
		return [
			'Has body' => [new Block(), true],
			'Does not have body' => [null, false],
		];
	}

	/**
	 * @test
	 * @dataProvider hasBodyDataProvider
	 */
	public function user_can_check_if_method_has_body($actualBody, bool $expectedHasBody)
	{
		$method = ClassMethod::name('test');

		if ($actualBody) {
			$method->setBody($actualBody);
		}


		$actualHasBody = $method->hasBody();


		$this->assertEquals($actualHasBody, $expectedHasBody);
	}

	/** @test */
	public function user_can_set_method_body_using_fluent_alias()
	{
		$method = ClassMethod::name('activate');


		$method->body(Block::fromArray([
			Block::invokeMethod(
				new This(),
				'setActive',
				[true]
			)
		]));
		$printed = $method->print();


		$this->assertStringContainsString("public function activate()\n{\n    \$this->setActive(true);\n}", $printed);
	}

	/** @test */
	public function user_can_get_body_using_fluent_alias()
	{
		$method = ClassMethod::name('test')->setBody(Block::fromArray([]));


		$body = $method->body();


		$this->assertInstanceOf(Block::class, $body);
	}

	/** @test */
	public function body_will_delegate_imports_to_method()
	{
		$method = ClassMethod::name('test')->setBody(
			Block::fromArray([
				Block::assign(
					Variable::name('user'),
					Block::instantiate(new Importable('App\Models\User'))
				),
				Block::assign(
					Reference::objectProperty(
						Variable::name('user'),
						'carMark',
					),
					Reference::classReference(new Importable('App\Cars\BMW')),
				),
				Block::return(
					Block::invokeStaticMethod(
						new Importable('App\Services\CarManufacturer'),
						'queueManufacture',
						[
							Reference::objectProperty(Variable::name('user'), 'carMark'),
						]
					)
				)
			])
		);


		/** @var \Shomisha\Stubless\ImperativeCode\UseStatement[] $imports */
		$imports = $method->getDelegatedImports();


		$this->assertCount(3, $imports);
		$this->assertEquals('App\Models\User', $imports['App\Models\User']->getName());
		$this->assertEquals('App\Cars\BMW', $imports['App\Cars\BMW']->getName());
		$this->assertEquals('App\Services\CarManufacturer', $imports['App\Services\CarManufacturer']->getName());
	}

	/** @test */
	public function class_methods_can_have_doc_blocks()
	{
		$method = ClassMethod::name('someMethod')->withDocBlock('This is a doc block');


		$printed = ClassTemplate::name('TestClass')->addMethod($method)->print();


		$this->assertStringContainsString("    /**\n     * This is a doc block\n     */\n", $printed);
	}

	/** @test */
	public function class_methods_can_generate_default_doc_blocks_automatically()
	{
		$method = ClassMethod::name('someMethod')->return(new Importable('App\Models\User'))->withArguments([
			Argument::name('test'),
			Argument::name('anotherTest')->type('string'),
			Argument::name('thirdTest')->type(new Importable('App\Models\ThirdTest', 'ThirdTestModel'))
		]);


		$method->withDefaultDocBlock();
		$printed = ClassTemplate::name('TestClass')->addMethod($method)->print();


		$this->assertStringContainsString("    /**\n     * @param \$test\n     * @param string \$anotherTest\n     * @param ThirdTestModel \$thirdTest\n     * @return User\n     */", $printed);
	}
}