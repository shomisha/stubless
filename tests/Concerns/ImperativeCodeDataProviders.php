<?php

namespace Shomisha\Stubless\Test\Concerns;

use Shomisha\Stubless\Comparisons\Comparison;
use Shomisha\Stubless\ImperativeCode\Block;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Values\Value;

trait ImperativeCodeDataProviders
{
	public function imperativeCodeDataProvider()
	{
		return [
			'Invoke function' => [Block::invokeFunction('doSomething'), 'doSomething();'],
			'Invoke static method' => [Block::invokeStaticMethod(Reference::staticReference(), 'doSomething'), 'static::doSomething();'],
			'Invoke method' => [Block::invokeMethod(Reference::this(), 'doSomething'), '$this->doSomething();'],
			'Return value' => [Block::return(15), 'return 15;'],
			'Assign value' => [Block::assign(Reference::objectProperty(Reference::this(), 'someProperty'), 'someValue'), "\$this->someProperty = 'someValue';"],
			'Standalone reference' => [Reference::variable('test'), '$test;'],
			'Standalon value' => [Value::string('I am alone.'), "'I am alone.';"],
			'Block of code' => [
				Block::fromArray([
					Block::assign(Reference::variable('user'), Block::invokeStaticMethod('User', 'find', [22])),
					Block::invokeMethod(Reference::variable('user'), 'deactivate'),
					Block::return(Reference::variable('user')),
				]),
				"\$user = User::find(22);\n    \$user->deactivate();\n\n    return \$user;"
			]
		];
	}

	public function referencesDataProvider()
	{
		return [
			"Variable" => [Reference::variable('testVar'), '$testVar'],
			"This" => [Reference::this(), '$this'],
			"Array Key Reference" => [Reference::arrayFetch(Reference::variable('testArray'), 'testKey'), "\$testArray['testKey']"],
			"Object Property" => [Reference::objectProperty(Reference::variable('testVar'), 'testProperty'), '$testVar->testProperty'],
			"Class Reference" => [Reference::classReference('User'), 'User::class'],
			"Static Reference" => [Reference::staticReference(), 'static::class'],
			"Self Reference" => [Reference::selfReference(), 'self::class'],
			"Static Property" => [Reference::staticProperty('User', 'totalCount'), 'User::$totalCount'],
		];
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

	public function comparisonsDataProvider()
	{
		return [
			'Equals' => [Comparison::equals(1, 5), '1 == 5'],
			'Equals strict' => [Comparison::equalsStrict(1, '1'), "1 === '1'"],
			'Not equals' => [Comparison::notEquals(2, 2), '2 != 2'],
			'Not equals strict' => [Comparison::notEqualsStrict(4, '4'), "4 !== '4'"],
			'Greater than' => [Comparison::greaterThan('test', 3), "'test' > 3"],
			'Greater than equals' => [Comparison::greaterThanEquals(4, 4), '4 >= 4'],
			'Lesser than' => [Comparison::lesserThan(4, 2), '4 < 2'],
			'Lesser than equals' => [Comparison::lesserThanEquals('asd', 'dsa'), "'asd' <= 'dsa'"],
		];
	}

	public function primeValuesDataProvider()
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

	public function arrayablesDataProvider()
	{
		return [
			'Invoke function' => [Block::invokeFunction('doSomething'), 'doSomething()'],
			'Invoke static method' => [Block::invokeStaticMethod(Reference::staticReference(), 'doSomething'), 'static::doSomething()'],
			'Invoke method' => [Block::invokeMethod(Reference::this(), 'doSomething'), '$this->doSomething()'],
			"Variable" => [Reference::variable('testVar'), '$testVar'],
			"Object Property" => [Reference::objectProperty(Reference::variable('testVar'), 'testProperty'), '$testVar->testProperty'],
			"Static Property" => [Reference::staticProperty('User', 'totalCount'), 'User::$totalCount'],
		];
	}

	public function assignableValuesDataProvider()
	{
		return array_merge(
			$this->referencesDataProvider(),
			$this->invocationsDataProvider(),
			$this->comparisonsDataProvider(),
			$this->primeValuesDataProvider(),
		);
	}

	public function assignableContainersDataProvider()
	{
		return [
			'Variable' => [Reference::variable('test'), "\$test"],
			'Array key' => [Reference::arrayFetch(Reference::variable('testArray'), 'testKey'), "\$testArray['testKey']"],
			'Object property' => [Reference::objectProperty(Reference::variable('testObject'), 'testProperty'), "\$testObject->testProperty"],
			'Static property' => [Reference::staticProperty('TestClass', 'testProperty'), "TestClass::\$testProperty"],
		];
	}
}