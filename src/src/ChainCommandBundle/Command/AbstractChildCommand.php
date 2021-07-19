<?php

namespace App\ChainCommandBundle\Command;

use App\ChainCommandBundle\Event\ChainCommandEvent;
use App\ChainCommandBundle\Event\CommandEvents;
use App\ChainCommandBundle\TagInterfaces\ChildCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractChildCommand extends Command implements ChildCommandInterface
{
    private ?AbstractParentCommand $parent = null;

    public function __construct(string $name = null, private EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($name);
        $this->eventDispatcher->dispatch(new ChainCommandEvent($this), CommandEvents::INIT);
    }

    public function setParent(AbstractParentCommand $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?AbstractParentCommand
    {
        return $this->parent;
    }
}