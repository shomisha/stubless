<?php

namespace Shomisha\Stubless\Test\Unit\Blocks;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\Blocks\ReturnBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\Value;

class ReturnTest extends TestCase
{
	/** @test */
	public function user_can_create_return_block_using_direct_constructor()
	{
		$return = new ReturnBlock(Variable::name('return'));


		$printed = $return->print();


		$this->assertStringContainsString('return $return', $printed);
	}

	public function valueDataProvider()
	{
		return [
			[Value::string('help me'), "return 'help me'"],
			[Value::integer(15), 'return 15'],
			[Value::float(3.14), 'return 3.14'],
			[Value::array([1, 2, 3]), 'return [1, 2, 3]'],
			[Value::boolean(true), 'return true'],
			[Value::null(), 'return null'],
		];
	}

	/**
	 * @test
	 * @dataProvider valueDataProvider
	 */
	public function user_can_add_prime_values_to_return_block($value, $expectedPrint)
	{
		$return = new ReturnBlock($value);


		$printed = $return->print();


		$this->assertStringContainsString($expectedPrint, $printed);
	}

	public function rawPrimeValuesDataProvider()
	{
		return [
			[15, 'return 15'],
			[42.22, 'return 42.22'],
			['some test string', "return 'some test string'"],
			[[1, 2, 3], 'return [1, 2, 3]'],
			[false, 'return false'],
			[null, 'return null'],
		];
	}

	/**
	 * @test
	 * @dataProvider rawPrimeValuesDataProvider
	 */
	public function user_can_add_raw_prime_values_when_creating_return_value_using_factory($value, $expectedPrint)
	{
		$return = Block::return($value);


		$printed = $return->print();


		$this->assertStringContainsString($expectedPrint, $printed);
	}

	/** @test */
	public function user_can_create_return_block_using_block_factory()
	{
		$return = Block::return(
			Block::invokeStaticMethod(
				Reference::classReference(new Importable('App\Models\User')),
				'finalize'
			)
		);


		$printed = $return->print();


		$this->assertStringContainsString('return User::finalize()', $printed);

		$imports = $return->getDelegatedImports();
		$this->assertCount(1, $imports);
	}
}