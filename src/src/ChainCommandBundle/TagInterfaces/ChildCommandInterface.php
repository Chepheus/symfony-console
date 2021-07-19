<?php

namespace App\ChainCommandBundle\TagInterfaces;

use App\ChainCommandBundle\Command\AbstractParentCommand;

interface ChildCommandInterface
{
    public function setParent(AbstractParentCommand $parent): void;
    public function getParent(): ?AbstractParentCommand;
}