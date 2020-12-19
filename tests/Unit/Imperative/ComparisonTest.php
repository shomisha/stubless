<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Comparison\Comparison;
use Shomisha\Stubless\Comparison\Equals;
use Shomisha\Stubless\Comparison\EqualsStrict;
use Shomisha\Stubless\Comparison\GreaterThan;
use Shomisha\Stubless\Comparison\GreaterThanEquals;
use Shomisha\Stubless\Comparison\LesserThan;
use Shomisha\Stubless\Comparison\LesserThanEquals;
use Shomisha\Stubless\Comparison\NotEquals;
use Shomisha\Stubless\Comparison\NotEqualsStrict;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\ImperativeCode\InvokeBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Values\AssignableValue;
use Shomisha\Stubless\Values\Value;

class ComparisonTest extends TestCase
{
	/** @test */
	public function user_can_perform_equal_comparison()
	{
		$comparison = Comparison::equals('test', 'anothertest');


		$printed = $comparison->print();


		$this->assertStringContainsString("'test' == 'anothertest';", $printed);
	}

	/** @test */
	public function user_can_perform_equal_comparison_using_direct_constructor()
	{
		$comparison = new Equals(AssignableValue::normalize('test'), AssignableValue::normalize('anothertest'));


		$printed = $comparison->print();


		$this->assertStringContainsString("'test' == 'anothertest';", $printed);
	}

	/** @test */
	public function user_can_perform_strict_equal_comparisons()
	{
		$comparison = Comparison::equalsStrict(5, 7);


		$printed = $comparison->print();


		$this->assertStringContainsString('5 === 7;', $printed);
	}

	/** @test */
	public function user_can_perform_strict_equal_comparisons_using_direct_constructor()
	{
		$comparison = new EqualsStrict(AssignableValue::normalize(5), AssignableValue::normalize(7));


		$printed = $comparison->print();


		$this->assertStringContainsString('5 === 7;', $printed);
	}

	/** @test */
	public function user_can_perform_not_equal_comparison()
	{
		$comparison = Comparison::notEquals('asd', 'dsa');


		$printed = $comparison->print();


		$this->assertStringContainsString("'asd' != 'dsa';", $printed);
	}

	/** @test */
	public function user_can_perform_not_equal_comparison_using_direct_constructor()
	{
		$comparison = new NotEquals(AssignableValue::normalize('asd'), AssignableValue::normalize('dsa'));


		$printed = $comparison->print();


		$this->assertStringContainsString("'asd' != 'dsa';", $printed);
	}

	/** @test */
	public function user_can_perform_not_equal_strict_comparisons()
	{
		$comparison = Comparison::notEqualsStrict(5, '5');


		$printed = $comparison->print();


		$this->assertStringContainsString("5 !== '5';", $printed);
	}

	/** @test */
	public function user_can_perform_not_equal_strict_comparisons_using_direct_constructor()
	{
		$comparison = new NotEqualsStrict(AssignableValue::normalize(5), AssignableValue::normalize('5'));


		$printed = $comparison->print();


		$this->assertStringContainsString("5 !== '5';", $printed);
	}

	/** @test */
	public function user_can_perform_greater_than_comparisons()
	{
		$comparison = Comparison::greaterThan(22, 21);


		$printed = $comparison->print();


		$this->assertStringContainsString('22 > 21;', $printed);
	}

	/** @test */
	public function user_can_perform_greater_than_comparisons_using_direct_constructor()
	{
		$comparison = new GreaterThan(AssignableValue::normalize(22), AssignableValue::normalize(21));


		$printed = $comparison->print();


		$this->assertStringContainsString('22 > 21;', $printed);
	}

	/** @test */
	public function user_can_perform_greater_than_equal_comparisons()
	{
		$comparison = Comparison::greaterThanEquals('someString', 'anotherString');


		$printed = $comparison->print();


		$this->assertStringContainsString("'someString' >= 'anotherString';", $printed);
	}

	/** @test */
	public function user_can_perform_greater_than_equal_comparisons_using_direct_constructor()
	{
		$comparison = new GreaterThanEquals(AssignableValue::normalize('someString'), AssignableValue::normalize('anotherString'));


		$printed = $comparison->print();


		$this->assertStringContainsString("'someString' >= 'anotherString';", $printed);
	}

	/** @test */
	public function user_can_perform_lesser_than_comparisons()
	{
		$comparison = Comparison::lesserThan(22, 21);


		$printed = $comparison->print();


		$this->assertStringContainsString('22 < 21;', $printed);
	}

	/** @test */
	public function user_can_perform_lesser_than_comparisons_using_direct_constructor()
	{
		$comparison = new LesserThan(AssignableValue::normalize(22), AssignableValue::normalize(21));


		$printed = $comparison->print();


		$this->assertStringContainsString('22 < 21;', $printed);
	}

	/** @test */
	public function user_can_perform_lesser_than_equal_comparisons()
	{
		$comparison = Comparison::lesserThanEquals(1, 'less than one');


		$printed = $comparison->print();


		$this->assertStringContainsString("1 <= 'less than one'", $printed);
	}

	/** @test */
	public function user_can_perform_lesser_than_equal_comparisons_using_direct_constructor()
	{
		$comparison = new LesserThanEquals(AssignableValue::normalize(1), AssignableValue::normalize('less than one'));


		$printed = $comparison->print();


		$this->assertStringContainsString("1 <= 'less than one'", $printed);
	}

	public function referencesDataProvider()
	{
		return [
			"Variable" => [Reference::variable('testVar'), '$testVar'],
			"This" => [Reference::this(), '$this'],
			"Object Property" => [Reference::objectProperty(Reference::variable('$testVar'), 'testProperty'), '$testVar->testProperty'],
			"Class Reference" => [Reference::classReference('User'), 'User::class'],
			"Static Reference" => [Reference::staticReference(), 'static::class'],
			"Self Reference" => [Reference::selfReference(), 'self::class'],
			"Static Property" => [Reference::staticProperty('User', 'totalCount'), 'User::$totalCount'],
		];
	}

	/**
	 * @test
	 * @dataProvider referencesDataProvider
	 */
	public function user_can_perform_comparisons_with_references(Reference $reference, string $printedReference)
	{
		$comparison = Comparison::notEqualsStrict($reference, 'testValue');


		$printed = $comparison->print();


		$this->assertStringContainsString("{$printedReference} !== 'testValue'", $printed);
	}

	public function invocationsDataProvider()
	{
		return [
			"Function" => [Block::invokeFunction('doSomething', [5]), 'doSomething(5)'],
			"Method" => [Block::invokeMethod(Reference::this(), 'doSomethingElse', ['test']), "\$this->doSomethingElse('test')"],
			"Static method" => [Block::invokeStaticMethod('User', 'setAvailable', [true]), 'User::setAvailable(true)'],
			"Instantiation" => [Block::instantiate('User'), 'new User()'],
		];
	}

	/**
	 * @test
	 * @dataProvider invocationsDataProvider
	 */
	public function user_can_perform_comparisons_with_invocations(InvokeBlock $invocation, string $printedInvocation)
	{
		$comparison = Comparison::greaterThanEquals('Am I Your Equal?', $invocation);


		$printed = $comparison->print();


		$this->assertStringContainsString("'Am I Your Equal?' >= {$printedInvocation};", $printed);
	}

	public function primeValueDataProvider()
	{
		return [
			"String" => ["test string", "'test string'"],
			"Wrapped String" => [Value::string("another test string"), "'another test string'"],
			"Integer" => [1, '1'],
			"Wrapped Integer" => [Value::integer(1), '1'],
			"Float" => [3.14, "3.14"],
			"Wrapped Float" => [Value::float(24.42), "24.42"],
			"Array" => [[1, 2, 3], "[1, 2, 3]"],
			"Wrapped Array" => [Value::array([1, 'string', false]), "[1, 'string', false]"],
			"Boolean" => [true, "true"],
			"Wrapped Boolean" => [Value::boolean(false), "false"],
		];
	}

	/**
	 * @test
	 * @dataProvider primeValueDataProvider
	 */
	public function user_can_perform_comparisons_with_prime_values($value, string $printedValue)
	{
		$comparison = Comparison::lesserThan($value, 24);


		$printed = $comparison->print();


		$this->assertStringContainsString("{$printedValue} < 24;", $printed);
	}
}