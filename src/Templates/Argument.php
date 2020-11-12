<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;

class Argument extends Template
{
	private string $name;

	private ?string $type;

	public function __construct(string $name, string $type = null)
	{
		$this->name = $name;
		$this->type = $type;
	}

	/** @return \PhpParser\Node\Param */
	public function constructNode(): Node
	{
		$argument = $this->getFactory()->param($this->name);

		if ($this->type !== null) {
			$argument->setType($this->type);
		}

		return $this->convertBuilderToNode($argument);
	}
}