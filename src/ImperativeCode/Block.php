<?php

namespace Shomisha\Stubless\ImperativeCode;

use Shomisha\Stubless\Abstractions\ImperativeCode;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\Contracts\ObjectContainer;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\DoWhileBlock;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\ForeachBlock;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\IfBlock;
use Shomisha\Stubless\ImperativeCode\ControlBlocks\WhileBlock;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Variable;
use Shomisha\Stubless\Values\AssignableValue;
use Shomisha\Stubless\Values\Value;

class Block extends ImperativeCode
{
	/** @var \Shomisha\Stubless\Abstractions\ImperativeCode[] */
	private array $code;

	/** @param \Shomisha\Stubless\Abstractions\ImperativeCode[] $code */
	public function __construct(array $code = [])
	{
		$this->code = $code;
	}

	public static function fromArray(array $code): Block
	{
		return new self($code);
	}

	public function addCode(ImperativeCode $code): self
	{
		$this->code[] = $code;

		return $this;
	}

	/** @param \Shomisha\Stubless\Abstractions\ImperativeCode[] $codes */
	public function addCodes(array $codes): self
	{
		$this->code = array_merge($this->code, $codes);

		return $this;
	}

	/** @return \Shomisha\Stubless\Abstractions\ImperativeCode[] */
	public function getCodes(): array
	{
		return $this->code;
	}

	public function getPrintableNodes(): array
	{
		$nodes = [];

		foreach ($this->code as $code) {
			$nodes = array_merge($nodes, $code->getPrintableNodes());
		}

		return $nodes;
	}

	public function getImportSubDelegates(): array
	{
		return $this->code ?? [];
	}

	public static function assign($variable, $value): AssignBlock
	{
		if (!$variable instanceof AssignableContainer) {
			if (is_string($variable)) {
				$variable = Variable::name($variable);
			} else {
				throw new \InvalidArgumentException(sprintf(
					"Method %s::assign() expects string or instance of %s as first argument.",
					self::class,
					AssignableContainer::class,
				));
			}
		}

		return new AssignBlock($variable, Value::normalize($value));
	}

	public static function return($returnValue): ReturnBlock
	{
		return new ReturnBlock(Value::normalize($returnValue));
	}

	/** @param \Shomisha\Stubless\Utilities\Importable|string $class */
	public static function instantiate($class, array $arguments = []): InstantiateBlock
	{
		return new InstantiateBlock($class, $arguments);
	}

	public static function invokeMethod(ObjectContainer $variable, string $name, array $arguments = []): InvokeMethodBlock
	{
		return new InvokeMethodBlock($variable, $name, $arguments);
	}

	public static function invokeStaticMethod($class, string $name, array $arguments = []): InvokeStaticMethodBlock
	{
		return new InvokeStaticMethodBlock(ClassReference::normalize($class), $name, $arguments);
	}

	public static function invokeFunction(string $name, array $arguments = []): InvokeFunctionBlock
	{
		return new InvokeFunctionBlock($name, $arguments);
	}

	public static function throw(ObjectContainer $exception): ThrowBlock
	{
		return new ThrowBlock($exception);
	}

	public static function if($condition): IfBlock
	{
		return new IfBlock(AssignableValue::normalize($condition));
	}

	public static function while($condition): WhileBlock
	{
		return new WhileBlock(AssignableValue::normalize($condition));
	}

	public static function doWhile($condition): DoWhileBlock
	{
		return new DoWhileBlock(AssignableValue::normalize($condition));
	}

	public static function foreach(Arrayable $array, $valueContainer): ForeachBlock
	{
		if (!$valueContainer instanceof Variable) {
			if (is_string($valueContainer)) {
				$valueContainer = Variable::name($valueContainer);
			} else {
				throw new \InvalidArgumentException(sprintf("Value cannot be safely normalized to a %s instance.", Value::class));
			}
		}

		return new ForeachBlock($array, $valueContainer);
	}
}