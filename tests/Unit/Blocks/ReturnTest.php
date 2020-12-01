<?php

namespace Shomisha\Stubless\Test\Unit\Blocks;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\Blocks\ReturnBlock;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Utilities\Importable;

class ReturnTest extends TestCase
{
	/** @test */
	public function user_can_create_return_block_using_direct_constructor()
	{
		$return = new ReturnBlock(Variable::name('return'));


		$printed = $return->print();


		$this->assertStringContainsString('return $return', $printed);
	}

	/** @test */
	public function user_can_add_prime_values_to_return_block()
	{
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

		// TODO: think about what to do here. Try figuring out a better, more abstract, way of handling importables.
		$imports = $return->getDelegatedImports();
		$this->assertCount(1, $imports);
	}
}