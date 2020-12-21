<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\ImperativeCode\AssignBlock;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\DeclarativeCode\ClassMethod;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;

class AssignTest extends TestCase
{
	use ImperativeCodeDataProviders;

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
	public function user_can_create_assign_block_using_block_factory()
	{
		$assign = Block::assign(
			Variable::name('userAge'),
			Reference::objectProperty(Variable::name('user'), 'age')
		);


		$printed = $assign->print();


		$this->assertStringContainsString('$userAge = $user->age', $printed);
	}

	/** @test */
	public function user_can_pass_raw_values_when_creating_block_using_factory()
	{
		$assign = Block::assign(
			'test',
			false
		);


		$printed = $assign->print();


		$this->assertStringContainsString('$test = false', $printed);
	}

	/**
	 * @test
	 * @dataProvider assignableContainersDataProvider
	 */
	public function user_can_create_assign_blocks_with_any_assignable_container(AssignableContainer $container, string $printedContainer)
	{
		$assign = Block::assign($container, 'test');


		$printed = $assign->print();


		$this->assertStringContainsString("{$printedContainer} = 'test';", $printed);
	}

	/**
	 * @test
	 * @dataProvider assignableValuesDataProvider
	 */
	public function user_can_create_assign_blocks_with_any_assignable_value($value, string $printedValue)
	{
		$assignBlock = Block::assign('test', $value);


		$printed = $assignBlock->print();


		$this->assertStringContainsString("\$test = {$printedValue};", $printed);
	}

	public function invalidAssignBlockArgumentDataProvider()
	{
		return [
			[15],
			[false],
			[[1, 2, 3]],
			[ClassMethod::name('test')],
			[Block::return(15)],
		];
	}

	/**
	 * @test
	 * @dataProvider  invalidAssignBlockArgumentDataProvider
	 */
	public function user_cannot_pass_invalid_value_to_create_assign_block_when_using_factory($variable)
	{
		$this->expectException(\InvalidArgumentException::class);

		Block::assign($variable, 'test');
	}
}