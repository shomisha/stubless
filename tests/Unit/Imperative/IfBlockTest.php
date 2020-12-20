<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Comparisons\Comparison;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\IfBlock;
use Shomisha\Stubless\ImperativeCode\InvokeBlock;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\Value;

class IfBlockTest extends TestCase
{
	use ImperativeCodeDataProviders;

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

	/** @test */
	public function if_block_will_delegate_imports_from_its_elements()
	{
		$ifBlock = Block::if(Reference::classReference(new Importable('App\Models\User')))->then(
			Block::invokeStaticMethod(new Importable('App\Models\Post'), 'publishAll')
		)->elseif(
			Block::invokeStaticMethod(new Importable('App\Models\Author'), 'exists'),
			Block::invokeStaticMethod(new Importable('App\Models\Book'), 'publishAll')
		);


		$printed = $ifBlock->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('if (User::class)', $printed);
		$this->assertStringContainsString('use App\Models\Post;', $printed);
		$this->assertStringContainsString('Post::publishAll();', $printed);
		$this->assertStringContainsString('use App\Models\Author', $printed);
		$this->assertStringContainsString('Author::exists()', $printed);
		$this->assertStringContainsString('use App\Models\Book', $printed);
		$this->assertStringContainsString('Book::publishAll()', $printed);
	}
}