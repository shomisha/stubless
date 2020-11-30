<?php

namespace Shomisha\Stubless\Templates;

use Shomisha\Stubless\Templates\Concerns\HasName;

class UseStatement extends Template
{
	use HasName;

	private ?string $as;

	public function __construct(string $name, string $as = null)
	{
		$this->name = $name;
		$this->as = $as;
	}

	public function getAs(): ?string
	{
		return $this->as;
	}

	public function setAs(string $as): self
	{
		$this->as = $as;

		return $this;
	}

	/** @return \PhpParser\Builder\Use_ */
	public function getPrintableNodes(): array
	{
		$statement = $this->getFactory()->use($this->name);

		if ($this->as !== null) {
			$statement->as($this->as);
		}

		return [$this->convertBuilderToNode($statement)];
	}
}