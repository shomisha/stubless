<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ReturnBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\Value;

class ReturnTest extends TestCase
{
	use ImperativeCodeDataProviders;

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

	/**
	 * @test
	 * @dataProvider assignableValuesDataProvider
	 */
	public function user_can_add_any_assignable_values_to_return_block($value, string $printedValue)
	{
		$return = Block::return($value);


		$printed = $return->print();


		$this->assertStringContainsString("return {$printedValue};", $printed);
	}
}