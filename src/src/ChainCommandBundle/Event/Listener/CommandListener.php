<?php

namespace App\ChainCommandBundle\Event\Listener;

use App\ChainCommandBundle\Command\AbstractChildCommand;
use App\ChainCommandBundle\Command\AbstractParentCommand;
use App\ChainCommandBundle\Event\ChainCommandEvent;
use App\ChainCommandBundle\Event\CommandEvents;
use App\ChainCommandBundle\Exception\ChainPartException;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommandListener
{
    public function __construct(private EventDispatcherInterface $eventDispatcher){}

    /**
     * Listen console.command event for detect child command execution
     *
     * @param ConsoleEvent $event
     * @throws ChainPartException
     */
    public function onConsoleCommand(ConsoleEvent $event): void
    {
        $command = $event->getCommand();
        if ($command instanceof AbstractChildCommand) {
            throw new ChainPartException(
                sprintf(
                    'Error: %s command is a member of %s command chain and cannot be executed on its own.',
                    $command->getName(),
                    $command->getParent()->getName()
                )
            );
        }

        $this->eventDispatcher->dispatch(new ChainCommandEvent($command), CommandEvents::EXECUTE);
    }

    /**
     * Listen console.terminate event for execution child commands
     *
     * @param ConsoleEvent $event
     * @throws \Exception
     */
    public function onConsoleTerminate(ConsoleEvent $event): void
    {
        $command = $event->getCommand();
        if ($command instanceof AbstractParentCommand) {
            foreach ($command->getChains() as $chainCommand) {
                $this->eventDispatcher->dispatch(new ChainCommandEvent($chainCommand), CommandEvents::EXECUTE);
                $chainCommand->run(new ArrayInput([]), $event->getOutput());
                $this->eventDispatcher->dispatch(new ChainCommandEvent($chainCommand), CommandEvents::TERMINATE);
            }
        }
        $this->eventDispatcher->dispatch(new ChainCommandEvent($command), CommandEvents::TERMINATE);
    }

}