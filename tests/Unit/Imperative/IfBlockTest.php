<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Comparisons\Comparison;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\IfBlock;
use Shomisha\Stubless\ImperativeCode\InvokeBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Test\Concerns\AssignableValueDataProviders;
use Shomisha\Stubless\Values\Value;

class IfBlockTest extends TestCase
{
	use AssignableValueDataProviders;

	/** @test */
	public function user_can_create_if_block_using_factory_method()
	{
		$ifBlock = Block::if('test')->then(Block::fromArray([
			Block::invokeFunction('doSomething'),
		]));


		$printed = $ifBlock->print();


		$this->assertStringContainsString("if ('test') {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider comparisonsDataProvider
	 */
	public function user_can_create_if_block_using_comparison(Comparison $comparison, string $printedComparison)
	{
		$ifBlock = Block::if($comparison)->then(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $ifBlock->print();


		$this->assertStringContainsString("if ({$printedComparison}) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider invocationsDataProvider
	 */
	public function user_can_create_if_block_using_invokable(InvokeBlock $invocation, string $printedInvocation)
	{
		$ifBlock = Block::if($invocation)->then(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $ifBlock->print();


		$this->assertStringContainsString("if ({$printedInvocation}) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider referencesDataProvider
	 */
	public function user_can_create_if_block_using_reference(Reference $reference, string $printedReference)
	{
		$ifBlock = Block::if($reference)->then(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $ifBlock->print();


		$this->assertStringContainsString("if ({$printedReference}) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider primeValuesDataProvider
	 */
	public function user_can_create_if_block_using_prime_value($value, string $printedValue)
	{
		$ifBlock = Block::if($value)->then(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $ifBlock->print();


		$this->assertStringContainsString("if ({$printedValue}) {\n    doSomething();\n}", $printed);
	}

	/** @test */
	public function user_can_create_if_block_using_direct_constructor()
	{
		$ifBlock = new IfBlock(
			Block::invokeFunction('checkSomething'),
			Block::fromArray([Block::invokeFunction('doSomething')])
		);


		$printed = $ifBlock->print();


		$this->assertStringContainsString("if (checkSomething()) {\n    doSomething();\n}", $printed);
	}

	public function imperativeCodeDataProvider()
	{
		return [
			'Invoke function' => [Block::invokeFunction('doSomething'), 'doSomething();'],
			'Invoke static method' => [Block::invokeStaticMethod(Reference::staticReference(), 'doSomething'), 'static::doSomething();'],
			'Invoke method' => [Block::invokeMethod(Reference::this(), 'doSomething'), '$this->doSomething();'],
			'Return value' => [Block::return(15), 'return 15;'],
			'Assign value' => [Block::assign(Reference::objectProperty(Reference::this(), 'someProperty'), 'someValue'), "\$this->someProperty = 'someValue';"],
			'Standalone reference' => [Reference::variable('test'), '$test;'],
			'Standalon value' => [Value::string('I am alone.'), "'I am alone.';"],
			'Block of code' => [
				Block::fromArray([
					Block::assign(Reference::variable('user'), Block::invokeStaticMethod('User', 'find', [22])),
					Block::invokeMethod(Reference::variable('user'), 'deactivate'),
					Block::return(Reference::variable('user')),
				]),
				"\$user = User::find(22);\n    \$user->deactivate();\n\n    return \$user;"
			]
		];
	}

	/**
	 * @test
	 * @dataProvider imperativeCodeDataProvider
	 */
	public function user_can_use_any_imperative_code_as_if_body(ImperativeCode $code, string $printedCode)
	{
		$ifCode = Block::if(5);


		$ifCode->then($code);
		$printed = $ifCode->print();


		$this->assertStringContainsString("if (5) {\n    {$printedCode}\n}", $printed);
	}

	/** @test */
	public function user_can_add_an_else_block_to_an_if_block()
	{
		$ifBlock = Block::if(Block::invokeFunction('checkSomething'));


		$ifBlock->else(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));
		$printed = $ifBlock->print();


		$this->assertStringContainsString("if (checkSomething()) {\n} else {\n    doSomething();\n}", $printed);
	}

	/** @test */
	public function user_can_add_elseif_blocks_to_if_block()
	{
		$ifBlock = Block::if(5)->then(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$ifBlock->elseif(3, Block::fromArray([
			Block::invokeFunction('doSomethingElse')
		]));
		$ifBlock->elseif(7, Block::fromArray([
			Block::invokeFunction('doAnotherThing')
		]));
		$printed = $ifBlock->print();


		$this->assertStringContainsString("if (5) {\n    doSomething();\n} elseif (3) {\n    doSomethingElse();\n} elseif (7) {\n    doAnotherThing();\n}", $printed);
	}
}