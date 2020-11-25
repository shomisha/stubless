<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\UseStatement;

class UseStatementTest extends TestCase
{
	/** @test */
	public function user_can_create_use_statement_with_alias()
	{
		$import = UseStatement::name(\App\Models\Challenge::class)->setAs('ChallengeModel');


		$printed = $import->print();


		$this->assertStringContainsString('use App\Models\Challenge as ChallengeModel;', $printed);
	}

	/** @test */
	public function user_can_create_use_statement_without_alias()
	{
		$import = UseStatement::name(\App\Models\Point::class);


		$printed = $import->print();


		$this->assertStringContainsString('use App\Models\Point;', $printed);
	}

	/** @test */
	public function user_can_get_import_name()
	{
		$import = UseStatement::name(\App\Models\Car::class);


		$name = $import->getName();


		$this->assertEquals('App\Models\Car', $name);
	}

	/** @test */
	public function user_can_set_import_name()
	{
		$import = UseStatement::name(\Test\SomeNamespace\Old::class);


		$import->setName(\Test\SomeNamespace\Neue::class);
		$printed = $import->print();


		$this->assertStringContainsString('use Test\SomeNamespace\Neue;', $printed);
	}

	/** @test */
	public function user_can_get_import_alias()
	{
		$import = UseStatement::name(\App\Models\Bicycle::class)->setAs('Bike');


		$alias = $import->getAs();


		$this->assertEquals('Bike', $alias);
	}
}