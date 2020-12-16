<?php

namespace Shomisha\Stubless\Test\Unit\Blocks;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\Blocks\InstantiateBlock;
use Shomisha\Stubless\Utilities\Importable;

class InstantiateTest extends TestCase
{
	/** @test */
	public function user_can_create_instantiate_block_using_direct_constructor()
	{
		$instantiate = new InstantiateBlock('App\Models\User');


		$printed = $instantiate->print();


		$this->assertStringContainsString('new App\Models\User()', $printed);
	}

	/** @test */
	public function user_can_create_instantiate_block_using_importable()
	{
		$instantiate = new InstantiateBlock(new Importable('App\Models\User'));


		$printed = $instantiate->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('new User()', $printed);

		$imports = $instantiate->getDelegatedImports();
		$this->assertCount(1, $imports);
		$this->assertEquals('App\Models\User', $imports['App\Models\User']->getName());
	}

	/** @test */
	public function user_can_add_arguments_to_instantiation()
	{
		$instantiate = new InstantiateBlock('App\Models\User', [
			[
				'first_name' => 'Misa',
				'last_name' => 'Kovic',
				'nickname' => 'Shomisha',
			]
		]);


		$printed = $instantiate->print();


		$this->assertStringContainsString("new App\Models\User(['first_name' => 'Misa', 'last_name' => 'Kovic', 'nickname' => 'Shomisha'])", $printed);
	}

	/** @test */
	public function user_can_create_instantiate_block_using_block_factory()
	{
		$instantiate = Block::instantiate('App\Models\User');


		$printed = $instantiate->print();


		$this->assertStringContainsString('new App\Models\User', $printed);
	}
}