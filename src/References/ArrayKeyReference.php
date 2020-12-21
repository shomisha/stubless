<?php

namespace Shomisha\Stubless\References;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use Shomisha\Stubless\Contracts\Arrayable;
use Shomisha\Stubless\Contracts\AssignableContainer;
use Shomisha\Stubless\Values\AssignableValue;

class ArrayKeyReference extends Reference implements AssignableContainer
{
	private Arrayable $array;

	/** @var \Shomisha\Stubless\Values\AssignableValue[] */
	private array $keys;

	public function __construct(Arrayable $array, AssignableValue $key)
	{
		$this->array = $array;
		$this->keys = [$key];
	}

	public function nest(...$keys)
	{
		$this->keys = array_merge(
			$this->keys,
			array_map(function ($key) {
				return AssignableValue::normalize($key);
			}, $keys)
		);

		return $this;
	}

	public function getPrintableNodes(): array
	{
		$keys = $this->keys;

		$firstKey = array_shift($keys);
		$arrayAccessExpr = new ArrayDimFetch($this->array->getPrintableArrayExpr(), $firstKey->getAssignableValueExpression());

		return [
			$this->nestArrayKeys($arrayAccessExpr, $keys),
		];
	}

	public function getAssignableContainerExpression(): Expr
	{
		return $this->getPrintableNodes()[0];
	}

	protected function getImportSubDelegates(): array
	{
		return $this->extractImportDelegatesFromArray([
			$this->array,
			...$this->keys,
		]);
	}

	private function nestArrayKeys(ArrayDimFetch $root, array $keys): ArrayDimFetch
	{
		if ($newKey = array_shift($keys)) {
			$root = $this->nestArrayKeys(new ArrayDimFetch($root, $newKey->getAssignableValueExpression()), $keys);
		}

		return $root;
	}
}