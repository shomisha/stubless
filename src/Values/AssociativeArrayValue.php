<?php

namespace Shomisha\Stubless\Values;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;

class AssociativeArrayValue extends ArrayValue
{
	/** @var \Shomisha\Stubless\Values\AssignableValue[] */
	protected array $keys;

	public function __construct(array $keys, array $elements)
	{
		[$this->keys, $this->elements] = $this->prepareKeysAndElements($keys, $elements);
	}

	public function add(AssignableValue $key, AssignableValue $value)
	{
		$this->keys[] = $key;
		$this->elements[] = $value;
	}

	protected function getPrintableRaw()
	{
		$elements = [];

		foreach ($this->keys as $offset => $key) {
			$elements[] = new ArrayItem(
				$this->elements[$offset]->getPrintableNodes()[0],
				$key->getPrintableNodes()[0]
			);
		}

		return new Array_($elements);
	}

	private function prepareKeysAndElements(array $keys, array $elements): array
	{
		$totalKeys = count($keys);
		$totalElements = count($elements);

		if ($totalKeys > $totalElements) {
			$elements = array_pad($elements, $totalKeys, null);
		}

		if ($totalElements > $totalKeys) {
			$elements = array_slice($elements, 0, $totalKeys);
		}

		return [
			array_map(fn($key) => Value::normalize($key), $keys),
			array_map(fn($element) => Value::normalize($element), $elements)
		];
	}

	protected function getImportSubDelegates(): array
	{
		return array_merge(
			$this->extractImportDelegatesFromArray($this->keys),
			$this->extractImportDelegatesFromArray($this->elements)
		);
	}
}