<?php

namespace Shomisha\Stubless\Test\Unit;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Blocks\Block;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Templates\UseStatement;
use Shomisha\Stubless\Utilities\Importable;

class ImportableTest extends TestCase
{
	/**
	 * @test
	 * @testWith ["Illuminate\\Database\\Eloquent\\Model", "Model"]
	 *			 ["Illuminate\\Routing\\Controller", "Controller"]
	 *			 ["App\\Contracts\\Searchable", "Searchable"]
	 */
	public function importable_will_extract_short_class_name_from_full_class_name($fullClassName, $expectedShortName)
	{
		$importable = new Importable($fullClassName);


		$actualShortName = $importable->getShortName();


		$this->assertEquals($expectedShortName, $actualShortName);
	}

	/** @test */
	public function importable_will_use_alias_instead_of_short_class_name_if_one_is_provided()
	{
		$importable = new Importable('App\Contracts\Taggable', 'TaggableContract');


		$shortName = $importable->getShortName();


		$this->assertEquals('TaggableContract', $shortName);
	}

	/** @test */
	public function importable_can_prepare_import_statement_based_on_full_class_name_provided()
	{
		$importable = new Importable('App\Models\User');


		$useStatement = $importable->getImportStatement();


		$this->assertInstanceOf(UseStatement::class, $useStatement);
		$this->assertEquals('App\Models\User', $useStatement->getName());
	}

	/** @test */
	public function prepared_import_statement_will_contain_alias_if_one_is_provided()
	{
		$importable = new Importable('App\Models\User', 'UserModel');


		$useStatement = $importable->getImportStatement();


		$this->assertInstanceOf(UseStatement::class, $useStatement);
		$this->assertEquals('App\Models\User', $useStatement->getName());
		$this->assertEquals('UserModel', $useStatement->getAs());
	}

	/** @test */
	public function importable_can_return_full_class_name()
	{
		$importable = new Importable('App\Http\Controllers\CustomersController');


		$fullClassName = $importable->getFullName();


		$this->assertEquals('App\Http\Controllers\CustomersController', $fullClassName);
	}

	/** @test */
	public function importable_will_not_be_printed_more_than_once()
	{
		$userVar = Variable::name('user');
		$block = Block::fromArray([
			Block::assign(
				$userVar,
				Block::invokeStaticMethod(
					Reference::classReference(new Importable('App\Models\User')),
					'first'
				)
			),
			Block::invokeMethod(
				$userVar,
				'assignMentor',
				[
					Block::invokeStaticMethod(
						Reference::classReference(new Importable('App\Models\User')),
						'find',
						[15]
					)
				]
			)
		]);


		$printed = $block->print();


		$this->assertEquals(1, substr_count($printed, 'use App\Models\User;'));
	}
}