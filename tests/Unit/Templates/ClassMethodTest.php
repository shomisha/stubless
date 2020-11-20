<?php

namespace Shomisha\Stubless\Test\Unit\Templates;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Templates\ClassMethod;
use Shomisha\Stubless\Templates\ClassTemplate;
use Shomisha\Stubless\Utilities\Importable;

class ClassMethodTest extends TestCase
{
	/** @test */
	public function users_can_create_methods_with_all_characteristics()
	{
		$method = ClassMethod::name('doSomething');

		$method->makeFinal();

		$method->makeProtected();

		$method->addArgument(Argument::name('firstParameter'));
		$method->addArgument(Argument::name('secondParameter')->type('string'));

		$method->return('bool');


		$printed = $method->print();


		$this->assertStringContainsString("protected final function doSomething(\$firstParameter, string \$secondParameter) : bool\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_final_methods()
	{
		$method = ClassMethod::name('finalMethod')->makeFinal();


		$printed = $method->print();


		$this->assertStringContainsString("public final function finalMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_abstract_methods()
	{
		$method = ClassMethod::name('abstractMethod')->makeAbstract();


		$printed = $method->print();


		$this->assertStringContainsString("public abstract function abstractMethod();", $printed);
	}

	/** @test */
	public function users_can_create_public_methods()
	{
		$method = ClassMethod::name('publicMethod')->makePublic();


		$printed = $method->print();


		$this->assertStringContainsString("public function publicMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_protected_methods()
	{
		$method = ClassMethod::name('protectedMethod')->makeProtected();


		$printed = $method->print();


		$this->assertStringContainsString("protected function protectedMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_private_methods()
	{
		$method = ClassMethod::name('privateMethod')->makePrivate();


		$printed = $method->print();


		$this->assertStringContainsString("private function privateMethod()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_methods_with_importable_arguments()
	{
		$method = ClassMethod::name('methodWithImportableArgument');
		$method->addArgument(
			Argument::name('object')->type(new Importable(\App\Models\User::class))
		);

		$class = ClassTemplate::name('TestClass')->addMethod($method)->setNamespace('Test');


		$printed = $class->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("public function methodWithImportableArgument(User \$object)", $printed);
	}

	/** @test */
	public function users_can_create_methods_without_arguments()
	{
		$method = ClassMethod::name('methodWithoutArguments');


		$printed = $method->print();


		$this->assertStringContainsString("public function methodWithoutArguments()\n{\n}", $printed);
	}

	/** @test */
	public function users_can_create_methods_with_importable_return_types()
	{
		$method = ClassMethod::name('methodWithImportableReturnType');

		$method->return(new Importable(\App\Models\Concert::class, 'ConcertModel'));

		$class = ClassTemplate::name('TestClass')->setNamespace('Test');
		$class->addMethod($method);


		$printed = $class->print();


		$this->assertStringContainsString('use App\Models\Concert as ConcertModel;', $printed);
		$this->assertStringContainsString("public function methodWithImportableReturnType() : ConcertModel\n", $printed);
	}

	/** @test */
	public function users_can_create_methods_without_return_types()
	{
		$method = ClassMethod::name('methodWithoutReturnType');


		$printed = $method->print();


		$this->assertStringContainsString("public function methodWithoutReturnType()\n{\n}", $printed);
	}

	/** @test */
	public function user_can_get_method_return_type()
	{
		$method = ClassMethod::name('doSomething')->setReturnType('bool');


		$returnType = $method->getReturnType();


		$this->assertEquals('bool', $returnType);
	}

	/** @test */
	public function user_can_get_method_return_type_using_fluent_alias()
	{
		$method = ClassMethod::name('doSomething')->setReturnType('float');


		$returnType = $method->return();


		$this->assertEquals('float', $returnType);
	}
}