<?php

namespace Shomisha\Stubless\Test\Unit\Blocks;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\AssignBlock;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;

class AssignTest extends TestCase
{
	/** @test */
	public function user_can_create_assign_block_using_direct_constructor()
	{
		$assign = new AssignBlock(
			Reference::objectProperty(Variable::name('user'), 'first_name'),
			Block::invokeMethod(Variable::name('request'), 'input', ['first_name']),
		);


		$printed = $assign->print();


		$this->assertStringContainsString("\$user->first_name = \$request->input('first_name')", $printed);
	}

	/** @test */
	public function user_can_pass_values_to_assign_block()
	{

	}

	/** @test */
	public function user_can_create_assign_block_using_block_factory()
	{
		$assign = Block::assign(
			Variable::name('userAge'),
			Reference::objectProperty(Variable::name('user'), 'age')
		);


		$printed = $assign->print();


		$this->assertStringContainsString('$userAge = $user->age', $printed);
	}
}