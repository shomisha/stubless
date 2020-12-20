<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Comparisons\Comparison;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\DoWhileBlock;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\WhileBlock;
use Shomisha\Stubless\ImperativeCode\InvokeBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;

class WhileBlockTest extends TestCase
{
	use ImperativeCodeDataProviders;

	/** @test */
	public function user_can_create_while_block_using_factory_method()
	{
		$whileBlock = Block::while('test')->do(Block::fromArray([
			Block::invokeFunction('doSomething'),
		]));


		$printed = $whileBlock->print();


		$this->assertStringContainsString("while ('test') {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider comparisonsDataProvider
	 */
	public function user_can_create_while_block_using_comparison(Comparison $comparison, string $printedComparison)
	{
		$whileBlock = Block::while($comparison)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $whileBlock->print();


		$this->assertStringContainsString("while ({$printedComparison}) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider invocationsDataProvider
	 */
	public function user_can_create_while_block_using_invokable(InvokeBlock $invocation, string $printedInvocation)
	{
		$whileBlock = Block::while($invocation)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $whileBlock->print();


		$this->assertStringContainsString("while ({$printedInvocation}) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider referencesDataProvider
	 */
	public function user_can_create_while_block_using_reference(Reference $reference, string $printedReference)
	{
		$whileBlock = Block::while($reference)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $whileBlock->print();


		$this->assertStringContainsString("while ({$printedReference}) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider primeValuesDataProvider
	 */
	public function user_can_create_while_block_using_prime_value($value, string $printedValue)
	{
		$whileBlock = Block::while($value)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $whileBlock->print();


		$this->assertStringContainsString("while ({$printedValue}) {\n    doSomething();\n}", $printed);
	}

	/** @test */
	public function user_can_create_while_block_using_direct_constructor()
	{
		$whileBlock = new WhileBlock(
			Block::invokeFunction('checkSomething'),
			Block::fromArray([Block::invokeFunction('doSomething')])
		);


		$printed = $whileBlock->print();


		$this->assertStringContainsString("while (checkSomething()) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider imperativeCodeDataProvider
	 */
	public function user_can_use_any_imperative_code_as_while_body(ImperativeCode $code, string $printedCode)
	{
		$whileBlock = Block::while(5);


		$whileBlock->do($code);
		$printed = $whileBlock->print();


		$this->assertStringContainsString("while (5) {\n    {$printedCode}\n}", $printed);
	}

	/** @test */
	public function user_can_create_do_while_block_using_factory_method()
	{
		$doWhileBlock = Block::doWhile('test')->do(Block::fromArray([
			Block::invokeFunction('doSomething'),
		]));


		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    doSomething();\n} while ('test');", $printed);
	}

	/**
	 * @test
	 * @dataProvider comparisonsDataProvider
	 */
	public function user_can_create_do_while_block_using_comparison(Comparison $comparison, string $printedComparison)
	{
		$doWhileBlock = Block::doWhile($comparison)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    doSomething();\n} while ({$printedComparison});", $printed);
	}

	/**
	 * @test
	 * @dataProvider invocationsDataProvider
	 */
	public function user_can_create_do_while_block_using_invokable(InvokeBlock $invocation, string $printedInvocation)
	{
		$doWhileBlock = Block::doWhile($invocation)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    doSomething();\n} while ({$printedInvocation});", $printed);
	}

	/**
	 * @test
	 * @dataProvider referencesDataProvider
	 */
	public function user_can_create_do_while_block_using_reference(Reference $reference, string $printedReference)
	{
		$doWhileBlock = Block::doWhile($reference)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    doSomething();\n} while ({$printedReference});", $printed);
	}

	/**
	 * @test
	 * @dataProvider primeValuesDataProvider
	 */
	public function user_can_create_do_while_block_using_prime_value($value, string $printedValue)
	{
		$doWhileBlock = Block::doWhile($value)->do(Block::fromArray([
			Block::invokeFunction('doSomething')
		]));


		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    doSomething();\n} while ({$printedValue});", $printed);
	}

	/** @test */
	public function user_can_create_do_while_block_using_direct_constructor()
	{
		$doWhileBlock = new DoWhileBlock(
			Block::invokeFunction('checkSomething'),
			Block::fromArray([Block::invokeFunction('doSomething')])
		);


		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    doSomething();\n} while (checkSomething());", $printed);
	}

	/**
	 * @test
	 * @dataProvider imperativeCodeDataProvider
	 */
	public function user_can_use_any_imperative_code_as_do_while_body(ImperativeCode $code, string $printedCode)
	{
		$doWhileBlock = Block::doWhile(5);


		$doWhileBlock->do($code);
		$printed = $doWhileBlock->print();


		$this->assertStringContainsString("do {\n    {$printedCode}\n} while (5);", $printed);
	}
}