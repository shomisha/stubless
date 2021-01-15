<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Contracts\ObjectContainer;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ChainedMethodBlock;
use Shomisha\Stubless\ImperativeCode\InvokeFunctionBlock;
use Shomisha\Stubless\ImperativeCode\InvokeMethodBlock;
use Shomisha\Stubless\ImperativeCode\InvokeStaticMethodBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\This;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;

class InvokeTest extends TestCase
{
	use ImperativeCodeDataProviders;

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
	public function user_can_pass_in_importable_as_argument_to_invoked_function()
	{
		$invokeFunction = new InvokeFunctionBlock('testFunction', [
			'first-argument',
			Reference::classReference(new Importable('App\Models\User')),
		]);


		$printed = $invokeFunction->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("testFunction('first-argument', User::class)", $printed);

		$imports = $invokeFunction->getDelegatedImports();
		$this->assertCount(1, $imports);
		$this->assertEquals('App\Models\User', $imports['App\Models\User']->getName());
	}

	/** @test */
	public function user_can_create_the_invoke_function_block_using_block_factory()
	{
		$invokeFunction = Block::invokeFunction('doSomething', [
			'firstParameter', true
		]);


		$printed = $invokeFunction->print();


		$this->assertStringContainsString("doSomething('firstParameter', true);", $printed);
	}

	/** @test */
	public function user_can_chain_method_calls_on_function_call()
	{
		$invokeFunction = Block::invokeFunction('findUser', [1]);


		$invokeFunction->chain('initialize')->chain('setUsername', ['testuser'])->chain('save');
		$printed = $invokeFunction->print();


		$this->assertStringContainsString("findUser(1)->initialize()->setUsername('testuser')->save();", $printed);
	}

	/** @test */
	public function user_can_get_chained_method_using_fluent_alias_method()
	{
		$invokeFunction = Block::invokeFunction('findUser', [1]);
		$invokeFunction->chain('activate');


		$chainedBlock = $invokeFunction->chain();


		$this->assertInstanceOf(ChainedMethodBlock::class, $chainedBlock);
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
	public function user_can_chain_method_calls_on_invoke_method_block()
	{
		$invokeMethod = Block::invokeMethod(Variable::name('user'), 'initialize');


		$invokeMethod->chain('setUsername', ['nix224'])->chain('save')->chain('refresh');
		$printed = $invokeMethod->print();


		$this->assertStringContainsString("\$user->initialize()->setUsername('nix224')->save()->refresh();", $printed);
	}

	/**
	 * @test
	 * @dataProvider objectContainersDataProvider
	 */
	public function user_can_create_the_invoke_method_block_using_any_object_container_instance(ObjectContainer $object, string $printedObjectContainer)
	{
		$invokeMethod = Block::invokeMethod($object, 'doSomething');


		$printed = $invokeMethod->print();


		$this->assertStringContainsString("{$printedObjectContainer}->doSomething()", $printed);
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


		$this->assertStringContainsString('use App\Models\User;', $printed);
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

	/** @test */
	public function user_can_create_the_invoke_static_method_block_using_string_as_class()
	{
		$invokeStaticMethod = Block::invokeStaticMethod('App\Models\User', 'doSomething');


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('App\Models\User::doSomething();', $printed);
	}

	/** @test */
	public function user_can_create_the_invoke_static_method_block_using_importable_as_class()
	{
		$invokeStaticMethod = Block::invokeStaticMethod(new Importable('App\Models\User'), 'doSomething');


		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('User::doSomething();', $printed);
	}

	/** @test */
	public function user_can_chain_method_calls_on_invoke_static_method_block()
	{
		$invokeStaticMethod = Block::invokeStaticMethod(Reference::classReference('User'), 'find', [1]);


		$invokeStaticMethod->chain('setActive', [true])->chain('setExpired', [false])->chain('notify');
		$printed = $invokeStaticMethod->print();


		$this->assertStringContainsString("User::find(1)->setActive(true)->setExpired(false)->notify();", $printed);
	}

	/**
	 * @test
	 * @dataProvider assignableValuesDataProvider
	 */
	public function user_can_pass_any_assignable_values_as_arguments_to_invoke_block($argument, string $expectedPrint)
	{
		$invokeBlock = Block::invokeMethod(Variable::name('test'), 'doSomething', [$argument]);


		$printed = $invokeBlock->print();


		$this->assertStringContainsString("\$test->doSomething({$expectedPrint})", $printed);
	}

	/** @test */
	public function user_can_invoke_with_no_arguments()
	{
		$invokeBlock = Block::invokeFunction('doSomething');


		$printed = $invokeBlock->print();


		$this->assertStringContainsString('doSomething()', $printed);
	}

	/** @test */
	public function chained_calls_will_delegate_imports()
	{
		$invokeBlock = Block::invokeFunction('db');


		$invokeBlock->chain('table', [
			Block::invokeStaticMethod(
				Reference::classReference(new Importable('App\Models\Changelog')),
				'getTable'
			)
		])->chain('where', [
			'changeable_type',
			Block::invokeStaticMethod(
				Reference::classReference(new Importable('App\Models\User')),
				'getMorphableType'
			)
		])->chain('first');
		$printed = $invokeBlock->print();


		$this->assertStringContainsString('use App\Models\Changelog;', $printed);
		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("db()->table(Changelog::getTable())->where('changeable_type', User::getMorphableType())->first();", $printed);
	}

	/** @test */
	public function chained_method_blocks_will_delegate_printing_to_parent()
	{
		$parentBlock = $this->getMockBuilder(InvokeFunctionBlock::class)->disableOriginalConstructor()->getMock();
		$chainedMethod = new ChainedMethodBlock($parentBlock, 'doSomething');

		$parentBlock->expects($this->once())->method('print');


		$chainedMethod->print();
	}
}