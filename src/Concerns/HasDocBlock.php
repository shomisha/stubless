<?php

namespace Shomisha\Stubless\Concerns;

use PhpParser\Builder;
use PhpParser\Comment\Doc;

trait HasDocBlock
{
	protected ?string $docBlock = null;

	abstract public function withDefaultDocBlock(): self;

	public function docBlock(?string $docBlock = '')
	{
		if ($docBlock === '') {
			return $this->getDocBlock();
		}

		return $this->withDocBlock($docBlock);
	}

	public function withDocBlock(?string $docBlock): self
	{
		$this->docBlock = $docBlock;

		return $this;
	}

	public function getDocBlock(): ?string
	{
		return $this->docBlock;
	}

	protected function setDocBlockCommentOnBuilder(Builder $builder): void
	{
		if ($docComment = $this->getDocBlockComment()) {
			$builder->setDocComment($docComment);
		}
	}

	protected function getDocBlockComment(): ?Doc
	{
		if ($this->docBlock === null) {
			return null;
		}

		return new Doc("/**\n" . $this->docBlock . "\n*/");
	}
}