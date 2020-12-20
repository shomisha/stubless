<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\ForeachBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;

class ForeachBlockTest extends TestCase
{
	use ImperativeCodeDataProviders;

	/** @test */
	public function user_can_create_foreach_block_using_factory_method()
	{
		$foreach = Block::foreach(Reference::objectProperty(Reference::variable('object'), 'arrayProperty'), Reference::variable('arrayElement'));


		$printed = $foreach->print();


		$this->assertStringContainsString("foreach (\$object->arrayProperty as \$arrayElement) {\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider arrayablesDataProvider
	 */
	public function user_can_create_foreach_block_with_any_arrayable_implementation(Arrayable $arrayable, string $printedArrayable)
	{
		$foreach = Block::foreach($arrayable, Reference::variable('test'));


		$printed = $foreach->print();


		$this->assertStringContainsString("foreach ({$printedArrayable} as \$test) {\n}", $printed);
	}

	/** @test */
	public function user_can_create_foreach_block_using_direct_constructor()
	{
		$foreach = new ForeachBlock(Reference::variable('someArray'), Reference::variable('arrayElement'), Block::invokeFunction('doSomething'));


		$printed = $foreach->print();


		$this->assertStringContainsString("foreach (\$someArray as \$arrayElement) {\n    doSomething();\n}", $printed);
	}

	/**
	 * @test
	 * @dataProvider imperativeCodeDataProvider
	 */
	public function user_use_any_imperative_code_as_foreach_body(ImperativeCode $code, string $printedCode)
	{
		$foreach = Block::foreach(Reference::variable('someArray'), Reference::variable('arrayElement'));


		$foreach->do($code);
		$printed = $foreach->print();


		$this->assertStringContainsString("foreach (\$someArray as \$arrayElement) {\n    {$printedCode}\n}", $printed);
	}

	/** @test */
	public function user_can_pass_variable_for_storing_key_in_foreach_block()
	{
		$foreach = Block::foreach(Reference::variable('someArray'), Reference::variable('value'));


		$foreach->withKey(Reference::variable('key'));
		$printed = $foreach->print();


		$this->assertStringContainsString("foreach (\$someArray as \$key => \$value) {\n}", $printed);
	}

	/** @test */
	public function foreach_will_delegate_imports_from_its_elements()
	{
		$foreach = Block::foreach(Reference::staticProperty(new Importable('App\Models\User'), 'allUsers'), Reference::variable('user'));
		$foreach->do(
			Block::invokeStaticMethod(new Importable('App\Models\Post'), 'deletePostsForUser', [Reference::variable('user')])
		);


		$printed = $foreach->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('use App\Models\Post;', $printed);

		$this->assertStringContainsString("foreach (User::\$allUsers as \$user) {\n    Post::deletePostsForUser(\$user);\n}", $printed);
	}
}