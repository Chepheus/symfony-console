<?php

namespace App\ChainCommandBundle\Command;

use App\ChainCommandBundle\Event\ChainCommandEvent;
use App\ChainCommandBundle\Event\CommandEvents;
use App\ChainCommandBundle\TagInterfaces\ParentCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractParentCommand extends Command implements ParentCommandInterface
{
    /**
     * @var AbstractChildCommand[]
     */
    private array $chains;

    public function __construct(string $name = null, private EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($name);
        $this->eventDispatcher->dispatch(new ChainCommandEvent($this), CommandEvents::INIT);
    }

    public function addToChain(AbstractChildCommand $command): void
    {
        $this->chains[] = $command;
        $this->eventDispatcher->dispatch(new ChainCommandEvent($command), CommandEvents::ADD_TO_CHAIN);
    }

    /**
     * @return AbstractChildCommand[]
     */
    public function getChains(): array
    {
        return $this->chains;
    }
}