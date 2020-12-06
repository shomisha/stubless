<?php

namespace Shomisha\Stubless\Test\Unit\Blocks;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\Blocks\InstantiateBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\Value;

class ValueTest extends TestCase
{
	/** @test */
	public function array_value_can_contain_instantiate_blocks()
	{
		$array = [
			Block::instantiate('App\Test\TestClass'),
			new InstantiateBlock('App\Test\AnotherTestClass', [1, 3, Reference::variable('test')]),
		];


		$printed = Value::array($array)->print();


		$this->assertStringContainsString("array(new App\Test\TestClass(), new App\Test\AnotherTestClass(1, 3, \$test))", $printed);
	}

	/** @test */
	public function array_values_can_contain_subarrays()
	{
		$array = [
			1,
			[1, 2],
			[3, 4, 5],
			['test'],
		];


		$printed = Value::array($array)->print();


		$this->assertStringContainsString("array(1, array(1, 2), array(3, 4, 5), array('test'))", $printed);
	}

	/** @test */
	public function array_subarrays_can_contain_instantiate_blocks()
	{
		$array = [
			1,
			[1, 2, 3],
			[
				Block::instantiate(new Importable('App\Test\TestClass')),
			],
		];


		$printed = Value::array($array)->print();


		$this->assertStringContainsString("array(1, array(1, 2, 3), array(new TestClass()))", $printed);
	}
}