<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use Shomisha\Stubless\Templates\Concerns\HasName;

class Constant extends Template
{
	use HasName;

	private $value = null;

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value): self
	{
		if (is_object($value)) {
			throw new \InvalidArgumentException("Invalid value for constant, object provided.");
		}

		$this->value = $value;

		return $this;
	}

	public function value($value): self
	{
		return $this->setValue($value);
	}

	public function constructNode(): Node
	{
		return new Node\Stmt\Const_([
			new Node\Const_($this->name, BuilderHelpers::normalizeValue($this->value))
		]);
	}
}