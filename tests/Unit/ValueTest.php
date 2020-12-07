<?php

namespace Shomisha\Stubless\Test\Unit;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Templates\ClassMethod;
use Shomisha\Stubless\Values\ArrayValue;
use Shomisha\Stubless\Values\AssignableValue;
use Shomisha\Stubless\Values\BooleanValue;
use Shomisha\Stubless\Values\FloatValue;
use Shomisha\Stubless\Values\IntegerValue;
use Shomisha\Stubless\Values\NullValue;
use Shomisha\Stubless\Values\StringValue;
use Shomisha\Stubless\Values\Value;

class ValueTest extends TestCase
{
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