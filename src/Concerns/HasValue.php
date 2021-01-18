<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder;
use PhpParser\Node\Expr;
use Shomisha\Stubless\References\ClassReference;
use Shomisha\Stubless\References\Reference;
use Shomisha\Stubless\Utilities\Importable;

trait HasValue
{
	/** @var mixed */
	protected $value;

	public function value($value = null)
	{
		if ($value === null) {
			return $this->getValue();
		}

		return $this->setValue($value);
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value): self
	{
		if ($value instanceof Importable) {
			$value = Reference::classReference($value);
		}

		$this->assertValueIsValid($value);

		$this->value = $value;

		return $this;
	}

	protected function assertValueIsValid($value)
	{
		if (is_object($value) && !($value instanceof ClassReference)) {
			throw new \InvalidArgumentException(sprintf("You cannot assign objects as values to %s.", static::class));
		}
	}

	protected function getValueExpr(): ?Expr
	{
		if (!isset($this->value)) {
			return null;
		}

		if ($this->value instanceof ClassReference) {
			return $this->value->getPrintableNodes()[0];
		}

		return $this->getFactory()->val($this->value);
	}
}