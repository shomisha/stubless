<?php

namespace Shomisha\Stubless\Test\Unit\Blocks;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\Blocks\InvokeFunctionBlock;
use Shomisha\Stubless\Blocks\InvokeMethodBlock;
use Shomisha\Stubless\Blocks\InvokeStaticMethodBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\This;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Utilities\Importable;

class InvokeTest extends TestCase
{
	/** @test */
	public function user_can_create_the_invoke_function_block_using_direct_constructor()
	{
		$invokeFunction = new InvokeFunctionBlock('testFunction', [
			15,
			Variable::name('test'),
			Block::invokeMethod(Variable::name('anotherTest'), 'doSomething', ['argument']),
		]);


		$printed = $invokeFunction->print();


		$this->assertStringContainsString("testFunction(15, \$test, \$anotherTest->doSomething('argument'))", $printed);
	}

	/** @test */
	public function user_can_pass_in_invokable_as_argument_to_invoked_function()
	{
		$invokeFunction = new InvokeFunctionBlock('testFunction', [
			'first-argument',
			Reference::classReference(new Importable('App\Models\User')),
		]);


		$printed = $invokeFunction->print();


		$this->assertStringContainsString("testFunction('first-argument', User::class)", $printed);

		$imports = $invokeFunction->getDelegatedImports();
		$this->assertCount(1, $imports);
		$this->assertEquals('App\Models\User', $imports['App\Models\User']->getName());
	}

	/** @test */
	public function user_can_create_invoke_method_block_using_direct_constructor()
	{
		$invokeMethod = new InvokeMethodBlock(Variable::name('user'), 'promote', ['project-manager']);


		$printed = $invokeMethod->print();


		$this->assertStringContainsString("\$user->promote('project-manager')", $printed);
	}

	/** @test */
	public function user_can_create_invoke_method_block_using_block_factory()
	{
		$invokeMethod = Block::invokeMethod(new This(), 'isImportant');


		$printed = $invokeMethod->print();


		$this->assertStringContainsString('$this->isImportant()', $printed);
	}

	/** @test */
	public function user_can_create_the_invoke_static_method_block_using_direct_constructor()
	{
		$invokeStaticMethod = new InvokeStaticMethodBlock(Reference::classReference('App\Models\User'), 'query');


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('App\Models\User::query()', $printed);
	}

	/** @test */
	public function user_can_create_the_invoke_static_method_class_using_importable()
	{
		$invokeStaticMethod = new InvokeStaticMethodBlock(Reference::classReference(new Importable('App\Models\User')), 'find', [15]);


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('User::find(15)', $printed);

		$imports = $invokeStaticMethod->getDelegatedImports();
		$this->assertCount(1, $imports);
		$this->assertEquals('App\Models\User', $imports['App\Models\User']->getName());
	}

	/** @test */
	public function user_can_create_the_invoke_static_method_block_using_static_reference()
	{
		$invokeStaticMethod = Block::invokeStaticMethod(Reference::staticReference(), 'doSomething');


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('static::doSomething()', $printed);
	}

	/** @test */
	public function user_can_create_the_invoke_static_method_block_using_self_reference()
	{
		$invokeStaticMethod = Block::invokeStaticMethod(Reference::selfReference(), 'doSomethingElse');


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('self::doSomethingElse()', $printed);
	}

	/** @test */
	public function user_can_create_the_invoke_static_method_block_using_block_factory()
	{
		$invokeStaticMethod = Block::invokeStaticMethod(Reference::classReference('Car'), 'stopManufacturing', [
			Block::invokeStaticMethod(Reference::classReference('CarFactory'), 'getMain'),
		]);


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('Car::stopManufacturing(CarFactory::getMain())', $printed);
	}

	public function rawValuesDataProvider()
	{
		return [
			[[true], "true"],
			[[15], "15"],
			[["exactly"], "'exactly'"],
			[[[1, 2, 3]], "[1, 2, 3]"],
			[[25, "to", "life"], "25, 'to', 'life'"],
		];
	}

	/**
	 * @test
	 * @dataProvider rawValuesDataProvider
	 */
	public function user_can_pass_raw_values_as_arguments_to_invoke_block(array $arguments, string $expectedPrint)
	{
		$invokeBlock = Block::invokeMethod(Variable::name('test'), 'doSomething', $arguments);


		$printed = $invokeBlock->print();


		$this->assertStringContainsString("\$test->doSomething({$expectedPrint})", $printed);
	}

	public function referenceDataProvider()
	{
		return [
			[[Variable::name('test')], "\$test"],
			[[Reference::this()], "\$this"],
			[[Reference::objectProperty(Variable::name('test'), 'testProperty')], "\$test->testProperty"],
			[[Reference::staticProperty('TestClass', 'testProperty')], "TestClass::\$testProperty"],
			[[Reference::selfReference()], 'self::class'],
			[[Reference::staticReference()], 'static::class'],
			[[Reference::classReference('TestClass')], 'TestClass::class'],
			[[Reference::this(), Reference::variable('anotherVar'), Reference::objectProperty(Variable::name("this"), "someProperty")], "\$this, \$anotherVar, \$this->someProperty"]
		];
	}

	/**
	 * @test
	 * @dataProvider referenceDataProvider
	 */
	public function user_can_pass_references_as_arguments_to_invoke_block(array $arguments, string $expectedPrint)
	{
		$invokeBlock = Block::invokeFunction('testFunction', $arguments);


		$printed = $invokeBlock->print();


		$this->assertStringContainsString("testFunction($expectedPrint)", $printed);
	}

	public function invocationDataProvider()
	{
		return [
			[[Block::invokeFunction('test', [true])], "test(true)"],
			[[Block::invokeMethod(Variable::name('testVar'), 'testMethod')], "\$testVar->testMethod()"],
			[[Block::invokeStaticMethod(Reference::classReference('TestClass'), 'testMethod')], "TestClass::testMethod()"],
		];
	}

	/**
	 * @test
	 * @dataProvider invocationDataProvider
	 */
	public function user_can_pass_other_invocations_as_arguments_to_invoke_block(array $arguments, string $expectedPrint)
	{
		$invokeBlock = Block::invokeMethod(Reference::this(), 'doSomething', $arguments);


		$printed = $invokeBlock->print();


		$this->assertStringContainsString("\$this->doSomething($expectedPrint)", $printed);
	}

	/** @test */
	public function user_can_invoke_with_no_arguments()
	{
		$invokeBlock = Block::invokeFunction('doSomething');


		$printed = $invokeBlock->print();


		$this->assertStringContainsString('doSomething()', $printed);
	}
}