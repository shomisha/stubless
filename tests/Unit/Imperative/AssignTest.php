<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\ImperativeCode\AssignBlock;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\DeclarativeCode\ClassMethod;

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

	/** @test */
	public function user_can_assign_to_object_properties()
	{
		$property = Reference::objectProperty(Variable::name('test'), 'testProperty');


		$assign = Block::assign($property, 'test');
		$printed = $assign->print();


		$this->assertStringContainsString("\$test->testProperty = 'test'", $printed);
	}

	/** @test */
	public function user_can_assign_to_variables()
	{
		$variable = Variable::name('testVariable');


		$assign = Block::assign($variable, 'test');
		$printed = $assign->print();


		$this->assertStringContainsString("\$testVariable = 'test'", $printed);
	}

	/** @test */
	public function user_can_assign_raw_values()
	{
		$value = 15;


		$printed = Block::assign('test', $value)->print();


		$this->assertStringContainsString("\$test = 15", $printed);
	}

	/** @test */
	public function user_can_assign_invocations()
	{
		$invocation = Block::invokeFunction('testFunction', [true]);


		$printed = Block::assign('test', $invocation)->print();


		$this->assertStringContainsString("\$test = testFunction(true)", $printed);
	}

	/** @test */
	public function user_can_assign_variables()
	{
		$variable = Variable::name('assignMe');


		$printed = Block::assign('test', $variable)->print();


		$this->assertStringContainsString("\$test = \$assignMe", $printed);
	}

	/** @test */
	public function user_can_assign_class_properties()
	{
		$property = Reference::staticProperty('TestClass', 'testProperty');


		$printed = Block::assign('test', $property)->print();


		$this->assertStringContainsString("\$test = TestClass::\$testProperty;", $printed);
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