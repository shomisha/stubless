<?php

namespace Shomisha\Stubless\Test\Unit;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\Constant;

class TemplateTest extends TestCase
{
	/** @test */
	public function templates_can_print_themselves()
	{
		$template = Constant::name('TEST')->value(15);


		$printed = $template->print();


		$this->assertStringContainsString('const TEST = 15', $printed);
	}

	/** @test */
	public function templates_can_save_themselves_to_the_filesystem()
	{
		$path = tempnam(sys_get_temp_dir(), 'print_test');

		$template = Constant::name('ANOTHER_TEST')->value('ANOTHER VALUE');


		$saved = $template->save($path);


		$this->assertTrue($saved);

		$savedContents = file_get_contents($path);
		$this->assertStringContainsString("const ANOTHER_TEST = 'ANOTHER VALUE'", $savedContents);

		unlink($path);
	}
}