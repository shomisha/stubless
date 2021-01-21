<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Contracts\ObjectContainer;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\ThrowBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;

class ThrowBlockTest extends TestCase
{
	use ImperativeCodeDataProviders;

	/** @test */
	public function user_can_create_throw_block_using_direct_constructor()
	{
		$throw = new ThrowBlock(Reference::objectProperty(
			Reference::variable('exceptionFactory'),
			'someImportantException'
		));


		$printed = $throw->print();


		$this->assertStringContainsString('throw $exceptionFactory->someImportantException;', $printed);
	}

	/** @test */
	public function user_can_create_throw_block_using_factory_method()
	{
		$throw = Block::throw(Block::instantiate(
			new Importable('App\Exceptions\UserNotFoundException'),
			['User with provided ID not present in database.']
		));


		$printed = $throw->print();


		$this->assertStringContainsString('use App\Exceptions\UserNotFoundException;', $printed);
		$this->assertStringContainsString("throw new UserNotFoundException('User with provided ID not present in database.');", $printed);
	}

	/**
	 * @test
	 * @dataProvider objectContainersDataProvider
	 */
	public function user_can_create_throw_block_using_any_object_container(ObjectContainer $exception, string $printedException)
	{
		$printedException = str_replace('(new SomeClass())', 'new SomeClass()', $printedException);
		$throw = Block::throw($exception);


		$printed = $throw->print();


		$this->assertStringContainsString("throw {$printedException};", $printed);
	}
}