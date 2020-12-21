<?php

namespace Shomisha\Stubless\References;

use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Values\AssignableValue;

abstract class Reference extends AssignableValue
{
	public static function variable(string $name): Variable
	{
		return Variable::name($name);
	}

	public static function this(): This
	{
		return new This();
	}

	public static function arrayFetch(Arrayable $array, $key): ArrayKeyReference
	{
		return new ArrayKeyReference($array, AssignableValue::normalize($key));
	}

	public static function objectProperty(Variable $variable, string $name): ObjectProperty
	{
		return new ObjectProperty($variable, $name);
	}

	public static function staticProperty($class, string $property): StaticProperty
	{
		$class = ClassReference::normalize($class);

		return new StaticProperty($class, $property);
	}

	public static function staticReference(): StaticReference
	{
		return new StaticReference();
	}

	public static function selfReference(): SelfReference
	{
		return new SelfReference();
	}

	/** @param string|\Shomisha\Stubless\Utilities\Importable $class */
	public static function classReference($class): ClassReference
	{
		return new ClassReference($class);
	}
}