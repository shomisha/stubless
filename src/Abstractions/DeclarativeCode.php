<?php

namespace Shomisha\Stubless\Abstractions;

abstract class DeclarativeCode extends Code
{
	public function print(): string
	{
		return $this->getFormatter()->format(
			$this->getPrinter()->prettyPrintFile(
				$this->getPrintableNodes()
			)
		);
	}
}