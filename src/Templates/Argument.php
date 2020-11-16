<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node;
use Shomisha\Stubless\Contracts\DelegatesImports;
use Shomisha\Stubless\Templates\Concerns\HasImports;
use Shomisha\Stubless\Templates\Concerns\HasName;

class Argument extends Template implements DelegatesImports
{
	use HasName, HasImports;

	private ?string $type;

	public function __construct(string $name, string $type = null)
	{
		$this->name = $name;
		$this->type = $type;
	}

	public function type($type = null)
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

	/** @param string|\Shomisha\Stubless\Utilities\Importable $type */
	public function setType($type): self
	{
		if ($this->isImportable($type)) {
			$this->type = $type->getShortName();
			$this->addImportable($type);
		} else {
			$this->type = $type;
		}

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

	public function getDelegatedImports(): array
	{
		return $this->getImports();
	}
}