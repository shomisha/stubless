<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;

class UseStatement extends Template
{
	private string $name;

	private ?string $as;

	public function __construct(string $name, string $as = null)
	{
		$this->name = $name;
		$this->as = $as;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getAs(): string
	{
		return $this->as;
	}

	public function setAs(string $as): self
	{
		$this->as = $as;

		return $this;
	}

	/** @return \PhpParser\Builder\Use_ */
	public function constructNode(): Node
	{
		$statement = $this->getFactory()->use($this->name);

		if ($this->as !== null) {
			$statement->as($this->as);
		}

		return $this->convertBuilderToNode($statement);
	}
}