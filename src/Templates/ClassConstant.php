<?php

namespace Shomisha\Stubless\Templates;

use PhpParser\Node\Stmt\ClassConst;
use Shomisha\Stubless\Enums\ClassAccess;
use Shomisha\Stubless\Templates\Concerns\HasAccessModifier;

class ClassConstant extends Constant
{
	use HasAccessModifier;

	public function __construct(string $name, ClassAccess $access = null)
	{
		parent::__construct($name);

		$this->access = $access ?? ClassAccess::PUBLIC();
	}

	public function getPrintableNodes(): array
	{
		/** @var \PhpParser\Node\Stmt\Const_ $stmt */
		$stmt = parent::getPrintableNodes()[0];

		return [new ClassConst($stmt->consts, $this->access->getPhpParserAccessModifier())];
	}
}