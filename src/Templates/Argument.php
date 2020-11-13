<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Templates\Concerns\HasName;

class Argument extends Template
{
	use HasName;

	private ?string $type;

	public function __construct(string $name, string $type = null)
	{
		$this->name = $name;
		$this->type = $type;
	}

	public function type(string $type = null)
	{
		if ($type === null) {
			return $this->getType();
		}

		return $this->setType($type);
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function setType(?string $type): self
	{
		$this->type = $type;

		return $this;
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