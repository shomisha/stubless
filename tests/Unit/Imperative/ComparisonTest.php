<?php

namespace Shomisha\Stubless\Test\Unit\Imperative;

use PHPUnit\Framework\TestCase;
use Shomisha\Stubless\Comparisons\Comparison;
use Shomisha\Stubless\Comparisons\Equals;
use Shomisha\Stubless\Comparisons\EqualsStrict;
use Shomisha\Stubless\Comparisons\GreaterThan;
use Shomisha\Stubless\Comparisons\GreaterThanEquals;
use Shomisha\Stubless\Comparisons\LesserThan;
use Shomisha\Stubless\Comparisons\LesserThanEquals;
use Shomisha\Stubless\Comparisons\NotEquals;
use Shomisha\Stubless\Comparisons\NotEqualsStrict;
use Shomisha\Stubless\ImperativeCode\InvokeBlock;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Test\Concerns\ImperativeCodeDataProviders;
use Shomisha\Stubless\Values\AssignableValue;

class ComparisonTest extends TestCase
{
	use ImperativeCodeDataProviders;

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

	/**
	 * @test
	 * @dataProvider primeValuesDataProvider
	 */
	public function user_can_perform_comparisons_with_prime_values($value, string $printedValue)
	{
		$comparison = Comparison::lesserThan($value, 24);


		$printed = $comparison->print();


		$this->assertStringContainsString("{$printedValue} < 24;", $printed);
	}

	/**
	 * @test
	 * @dataProvider comparisonsDataProvider
	 */
	public function user_can_perform_comparisons_with_other_comparisons(Comparison $otherComparison, string $printedOtherComparison)
	{
		$mainComparison = Comparison::greaterThan(22, $otherComparison);


		$printed = $mainComparison->print();


		$this->assertStringContainsString("22 > ({$printedOtherComparison});", $printed);
	}
}