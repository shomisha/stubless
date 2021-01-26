<?php

namespace Shomisha\Stubless\Test\Unit;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\DeclarativeCode\Argument;
use Shomisha\Stubless\ImperativeCode\UseStatement;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\InstantiateBlock;
use Shomisha\Stubless\ImperativeCode\InvokeFunctionBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\DeclarativeCode\ClassMethod;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Utilities\Importable;
use Shomisha\Stubless\Values\ArrayValue;
use Shomisha\Stubless\Values\AssignableValue;
use Shomisha\Stubless\Values\AssociativeArrayValue;
use Shomisha\Stubless\Values\BooleanValue;
use Shomisha\Stubless\Values\Closure;
use Shomisha\Stubless\Values\FloatValue;
use Shomisha\Stubless\Values\IntegerValue;
use Shomisha\Stubless\Values\NullValue;
use Shomisha\Stubless\Values\StringValue;
use Shomisha\Stubless\Values\Value;

class ValueTest extends TestCase
{
	use ImperativeCodeDataProviders;

	/** @test */
	public function user_can_create_string_value_using_direct_constructor()
	{
		$string = new StringValue('test');


		$printed = $string->print();


		$this->assertStringContainsString("'test'", $printed);
	}

	/** @test */
	public function user_can_create_string_value_using_value_factory()
	{
		$string = Value::string('test');


		$printed = $string->print();


		$this->assertStringContainsString("'test'", $printed);
	}

	/** @test */
	public function user_can_normalize_strings_to_values()
	{
		$string = 'test';


		$normalized = AssignableValue::normalize($string);


		$this->assertInstanceOf(StringValue::class, $normalized);
		$this->assertStringContainsString("'test'", $normalized->print());
	}

	/** @test */
	public function user_can_normalize_importables_to_values()
	{
		$importable = new Importable('App\Models\User');


		$value = AssignableValue::normalize($importable);


		$this->assertInstanceOf(StringValue::class, $value);
		$this->assertEquals('User', $value->getRaw());
		$this->assertEquals('App\Models\User', $value->getImports()['App\Models\User']->getName());
	}

	/** @test */
	public function user_can_create_integer_value_using_direct_constructor()
	{
		$integer = new IntegerValue(15);


		$printed = $integer->print();


		$this->assertStringContainsString('15', $printed);
	}

	/** @test */
	public function user_can_create_integer_value_using_value_factory()
	{
		$integer = Value::integer(15);


		$printed = $integer->print();


		$this->assertStringContainsString('15', $printed);
	}

	/** @test */
	public function user_can_normalize_integers_to_values()
	{
		$integer = 227;


		$normalized = AssignableValue::normalize($integer);


		$this->assertInstanceOf(IntegerValue::class, $normalized);
		$this->assertStringContainsString('227', $normalized->print());
	}

	/** @test */
	public function user_can_create_float_value_using_direct_constructor()
	{
		$float = new FloatValue(15.4);


		$printed = $float->print();


		$this->assertStringContainsString('15.4', $printed);
	}

	/** @test */
	public function user_can_create_float_value_using_value_factory()
	{
		$float = Value::float(3.14);


		$printed = $float->print();


		$this->assertStringContainsString('3.14', $printed);
	}

	/** @test */
	public function user_can_normalize_floats_to_values()
	{
		$float = 22.22;


		$normalized = AssignableValue::normalize($float);


		$this->assertInstanceOf(FloatValue::class, $normalized);
		$this->assertStringContainsString('22.22', $normalized->print());
	}

	/** @test */
	public function user_can_create_array_value_using_direct_constructor()
	{
		$array = new ArrayValue([
			15,
			22,
			false,
			'a string',
		]);


		$printed = $array->print();


		$this->assertStringContainsString("[15, 22, false, 'a string']", $printed);
	}

	/** @test */
	public function array_value_will_delegate_imports()
	{
		$array = Value::array([
			Block::instantiate(new Importable('App\Models\User')),
			Block::invokeStaticMethod('Post', 'publishAll')->addImport(UseStatement::name('App\Models\Post')),
		]);


		$imports = $array->getDelegatedImports();


		$this->assertCount(2, $imports);
		$this->assertEquals('App\Models\User', $imports['App\Models\User']->getName());
		$this->assertEquals('App\Models\Post', $imports['App\Models\Post']->getName());
	}

	/** @test */
	public function user_can_create_array_value_using_value_factory()
	{
		$array = Value::array([
			15,
			22,
			false,
			'a string',
		]);


		$printed = $array->print();


		$this->assertStringContainsString("[15, 22, false, 'a string']", $printed);
	}
	
	/** @test */
	public function array_value_can_contain_instantiate_blocks()
	{
		$array = [
			Block::instantiate('App\Test\TestClass'),
			new InstantiateBlock('App\Test\AnotherTestClass', [1, 3, Reference::variable('test')]),
		];


		$printed = Value::array($array)->print();


		$this->assertStringContainsString("[new App\Test\TestClass(), new App\Test\AnotherTestClass(1, 3, \$test)]", $printed);
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


		$this->assertStringContainsString("[1, [1, 2], [3, 4, 5], ['test']]", $printed);
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


		$printed = (new ArrayValue($array))->print();


		$this->assertStringContainsString("[1, [1, 2, 3], [new TestClass()]]", $printed);
	}

	/** @test */
	public function array_values_can_return_underlying_raw_arrays()
	{
		$arrayValue = Value::array([
			1, 2, false, 'string', true, Block::invokeFunction('setEnv', ['dev']),
		]);


		$raw = $arrayValue->getRaw();


		$this->assertEquals(1, $raw[0]);
		$this->assertEquals(2, $raw[1]);
		$this->assertEquals(false, $raw[2]);
		$this->assertEquals('string', $raw[3]);
		$this->assertEquals(true, $raw[4]);
		$this->assertInstanceOf(InvokeFunctionBlock::class, $raw[5]);
	}

	/** @test */
	public function array_will_delegate_imports()
	{
		$array = Value::array([
			Block::invokeStaticMethod(
				new Importable('App\Services\SomeClass'),
				'doSomething'
			),
			Reference::classReference(new Importable('App\Services\AnotherClass')),
			Value::closure([], Block::fromArray([
				Block::return(Reference::classReference(
					new Importable('App\Services\ThirdClass')
				))
			])),
		]);


		$printed = $array->print();


		$this->assertStringContainsString('use App\Services\SomeClass;', $printed);
		$this->assertStringContainsString('use App\Services\AnotherClass;', $printed);
		$this->assertStringContainsString('use App\Services\ThirdClass;', $printed);

		$this->assertStringContainsString("[SomeClass::doSomething(), AnotherClass::class, function () {\n    return ThirdClass::class;\n}];", $printed);
	}

	/** @test */
	public function user_can_create_associative_arrays_using_direct_constructor()
	{
		$associativeArray = new AssociativeArrayValue(
			[1, 'test', Block::invokeFunction('someFunction')],
			['first element', false, Reference::objectProperty(Reference::this(), 'someProperty')]
		);


		$printed = $associativeArray->print();


		$this->assertStringContainsString("[1 => 'first element', 'test' => false, someFunction() => \$this->someProperty];", $printed);
	}

	/** @test */
	public function user_can_create_associative_arrays_using_factory_method()
	{
		$associativeArray = Value::associativeArray(
			[Block::invokeStaticMethod('User', 'someStaticMethod'), Block::invokeMethod(Reference::this(), 'someMethod')],
			[Value::array([1, 2, 3]), Reference::classReference('User')]
		);


		$printed = $associativeArray->print();


		$this->assertStringContainsString("[User::someStaticMethod() => [1, 2, 3], \$this->someMethod() => User::class];", $printed);
	}

	/** @test */
	public function user_can_create_associative_array_with_more_keys_than_values()
	{
		$associativeArray = Value::associativeArray(
			[5, 4, 3, 2, 1],
			[1, 2, 3]
		);


		$printed = $associativeArray->print();


		$this->assertStringContainsString("[5 => 1, 4 => 2, 3 => 3, 2 => null, 1 => null];", $printed);
	}

	/** @test */
	public function user_can_create_associative_array_with_more_values_than_keys()
	{
		$associativeArray = Value::associativeArray(
			[1, 2, 3],
			[1, 2, 3, 4, 5]
		);


		$printed = $associativeArray->print();


		$this->assertStringContainsString("[1 => 1, 2 => 2, 3 => 3];", $printed);
	}

	/**
	* @test
	* @dataProvider assignableValuesDataProvider
	**/
	public function user_can_create_associative_arrays_using_any_assignable_values_as_keys($assignableValue, string $printedAssignableValue)
	{
		$associativeArray = Value::associativeArray(
			[$assignableValue],
			['some value']
		);


		$printed = $associativeArray->print();


		$this->assertStringContainsString("[{$printedAssignableValue} => 'some value'];", $printed);
	}

	/** @test */
	public function user_can_create_empty_associative_arrays()
	{
		$associativeArray = Value::associativeArray([], []);


		$printed = $associativeArray->print();


		$this->assertStringContainsString("[];", $printed);
	}

	/** @test */
	public function user_can_add_values_to_associative_arrays()
	{
		$associativeArray = Value::associativeArray(
			[1],
			[1]
		);


		$associativeArray->add(Block::invokeMethod(Reference::this(), 'getKey'), Block::invokeFunction('getValue'));
		$printed = $associativeArray->print();


		$this->assertStringContainsString("[1 => 1, \$this->getKey() => getValue()];", $printed);
	}

	/** @test */
	public function associative_arrays_will_delegate_imports()
	{
		$associativeArray = Value::associativeArray(
			[Block::invokeStaticMethod(new Importable('App\Models\User'), 'chiefUserKey')],
			[Block::invokeStaticMethod(new Importable('App\Models\Chief'), 'getUsersChief')]
		);


		$printed = $associativeArray->print();


		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString('use App\Models\Chief;', $printed);
		$this->assertStringContainsString("[User::chiefUserKey() => Chief::getUsersChief()];", $printed);
	}

	/** @test */
	public function user_can_normalize_arrays_to_values()
	{
		$array = [
			'first element',
			'second element',
			true,
			15,
			22.4
		];


		$normalized = AssignableValue::normalize($array);


		$this->assertInstanceOf(ArrayValue::class, $normalized);
		$this->assertStringContainsString("['first element', 'second element', true, 15, 22.4]", $normalized->print());
	}

	/** @test */
	public function user_can_create_boolean_value_using_direct_constructor()
	{
		$bool = new BooleanValue(false);


		$printed = $bool->print();


		$this->assertStringContainsString('false', $printed);
	}

	/** @test */
	public function user_can_create_boolean_value_using_value_factory()
	{
		$bool = Value::boolean(true);


		$printed = $bool->print();


		$this->assertStringContainsString('true', $printed);
	}

	/** @test */
	public function user_can_normalize_booleans_to_values()
	{
		$bool = false;


		$normalized = AssignableValue::normalize($bool);


		$this->assertInstanceOf(BooleanValue::class, $normalized);
		$this->assertStringContainsString('false', $normalized->print());
	}

	/** @test */
	public function user_can_create_closure_values_using_direct_constructor()
	{
		$testVar = Variable::name('test');
		$anotherTestVar = Variable::name('anotherTest');
		$closure = new Closure([
			Argument::name('test')->type('TestClass'),
			Argument::name('anotherTest')->type(new Importable('App\Classes\TestClass', 'AnotherTestClass'))
		], Block::fromArray([
			Block::invokeMethod($testVar, 'doSomething', [$anotherTestVar]),
			Block::invokeFunction('doSomethingElse', [$anotherTestVar])
		]));


		$printed = $closure->print();


		$this->assertStringContainsString('use App\Classes\TestClass as AnotherTestClass;', $printed);
		$this->assertStringContainsString("function (TestClass \$test, AnotherTestClass \$anotherTest) {\n    \$test->doSomething(\$anotherTest);\n    doSomethingElse(\$anotherTest);\n};", $printed);
	}

	/** @test */
	public function user_can_create_closure_values_using_factory()
	{
		$tableVar = Variable::name('table');
		$closure = Value::closure([
			Argument::name('table')->type(new Importable('Illuminate\Database\Schema\Blueprint'))
		], Block::fromArray([
			Block::invokeMethod($tableVar, 'increments', ['id']),
			Block::invokeMethod($tableVar, 'bigInteger', ['country_id'])->chain('unsigned'),
			Block::invokeMethod($tableVar, 'string', ['name']),
			Block::invokeMethod($tableVar, 'string', ['email'])->chain('unique'),
			Block::invokeMethod($tableVar, 'foreign', ['country_id'])->chain('references', ['id'])->chain('on', ['countries'])->chain('onDelete', ['set null']),
		]));


		$printed = $closure->print();


		$this->assertStringContainsString('use Illuminate\Database\Schema\Blueprint;', $printed);
		$this->assertStringContainsString('function (Blueprint $table) {', $printed);
		$this->assertStringContainsString("\$table->increments('id');", $printed);
		$this->assertStringContainsString("\$table->bigInteger('country_id')->unsigned();", $printed);
		$this->assertStringContainsString("\$table->string('name');", $printed);
		$this->assertStringContainsString("\$table->string('email')->unique();", $printed);
		$this->assertStringContainsString("\$table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');", $printed);
	}

	/** @test */
	public function user_can_create_closure_with_no_arguments_and_no_body()
	{
		$closure = Value::closure([]);


		$printed = $closure->print();


		$this->assertStringContainsString("function () {\n};", $printed);
	}

	/** @test */
	public function user_can_add_uses_variables_to_closure_methods()
	{
		$closure = Value::closure([])->uses(Variable::name('test'));


		$printed = $closure->print();


		$this->assertStringContainsString("function () use(\$test) {\n};", $printed);
	}

	/** @test */
	public function user_can_return_values_from_closures()
	{
		$closure = Value::closure([], Block::fromArray([
			Block::return(
				Block::invokeStaticMethod('SomeClass', 'doSomething')
			)
		]));


		$printed = $closure->print();


		$this->assertStringContainsString("function () {\n    return SomeClass::doSomething();\n};", $printed);
	}

	/** @test */
	public function closures_will_delegate_imports()
	{
		$closure = Value::closure([], Block::fromArray([
			Block::return(
				Block::invokeStaticMethod(
					new Importable('App\Services\SomeClass'),
					'doSomething',
					[
						Reference::classReference(new Importable('App\Services\AnotherClass'))
					]
				)->chain('doSomethingElse')->chain('with', [Block::instantiate(new Importable('App\Models\User'))])
			)
		]));


		$printed = $closure->print();


		$this->assertStringContainsString('use App\Services\SomeClass;', $printed);
		$this->assertStringContainsString('use App\Services\AnotherClass;', $printed);
		$this->assertStringContainsString('use App\Models\User;', $printed);
		$this->assertStringContainsString("function () {\n    return SomeClass::doSomething(AnotherClass::class)->doSomethingElse()->with(new User());\n};", $printed);
	}

	/** @test */
	public function user_can_create_null_value_using_direct_constructor()
	{
		$null = new NullValue();


		$printed = $null->print();


		$this->assertStringContainsString('null', $printed);
	}

	/** @test */
	public function user_can_create_null_value_using_value_factory()
	{
		$null = Value::null();


		$printed = $null->print();


		$this->assertStringContainsString('null', $printed);
	}

	/** @test */
	public function user_can_normalize_nulls_to_values()
	{
		$null = null;


		$normalized = AssignableValue::normalize($null);


		$this->assertInstanceOf(NullValue::class, $normalized);
		$this->assertStringContainsString('null', $normalized->print());
	}

	/** @test */
	public function user_can_normalize_assignable_value_instances_to_values()
	{
		$variable = Variable::name('test');


		$normalized = AssignableValue::normalize($variable);


		$this->assertInstanceOf(Variable::class, $variable);
	}

	/** @test */
	public function user_cannot_normalize_invalid_values_to_values()
	{
		$method = ClassMethod::name('test');


		$this->expectException(\InvalidArgumentException::class);


		AssignableValue::normalize($method);
	}
}