<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\ImperativeCode\AssignBlock;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\InvokeStaticMethodBlock;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\Value;

class BlockTest extends TestCase
{
	/** @test */
	public function user_can_print_standalone_block()
	{
		$postVar = Variable::name('post');
		$requestVar = Variable::name('request');

		$block = Block::fromArray([
			Block::assign(
				$postVar,
				Block::invokeStaticMethod(
					Reference::classReference(new Importable('App\Models\Post')),
					'find',
					[
						Block::invokeMethod(
							$requestVar,
							'input',
							['post_id']
						)
					]
				)
			),
			Block::assign(
				Reference::objectProperty(
					$postVar,
					'is_public'
				),
				Block::invokeMethod(
					$requestVar,
					'input',
					['is_public']
				),
			),
			Block::invokeMethod($postVar, 'update'),
		]);


		$printed = $block->print();


		$this->assertStringContainsString('use App\Models\Post;', $printed);
		$this->assertStringContainsString("\$post = Post::find(\$request->input('post_id'));", $printed);
		$this->assertStringContainsString("\$post->is_public = \$request->input('is_public');", $printed);
		$this->assertStringContainsString("\$post->update();", $printed);
	}

	/** @test */
	public function user_can_create_block_using_direct_constructor()
	{
		$block = new Block([
			new AssignBlock(Variable::name('test'), Value::integer(1))
		]);


		$printed = $block->print();


		$this->assertStringContainsString('$test = 1;', $printed);
	}

	/** @test */
	public function user_can_create_block_from_array_of_subblocks()
	{
		$block = Block::fromArray([
			Block::assign(
				Variable::name('user'),
				Block::instantiate(new Importable('App\Models\User'))
			),
			Block::invokeMethod(
				Variable::name('user'),
				'activate',
				[true]
			),
		]);


		$printed = $block->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("\$user = new User();\n\$user->activate(true);", $printed);
	}

	/** @test */
	public function user_can_add_subblock_to_block()
	{
		$block = new Block();

		$block->addCode(
			new InvokeStaticMethodBlock(
				new ClassReference(new Importable('App\Models\User')),
				'deleteAll'
			)
		);


		$printed = $block->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('User::deleteAll();', $printed);
	}

	/** @test */
	public function user_can_add_multiple_subblocks_to_block()
	{
		$block = new Block();

		$userVar = Variable::name('user');

		$block->addCodes([
			Block::assign(
				$userVar,
				Block::invokeStaticMethod(
					Reference::classReference((new Importable('App\Models\User'))),
					'find',
					[1]
				)
			),
			Block::invokeMethod(
				$userVar,
				'delete'
			),
		]);


		$printed = $block->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("\$user = User::find(1);\n\$user->delete();", $printed);
	}
}