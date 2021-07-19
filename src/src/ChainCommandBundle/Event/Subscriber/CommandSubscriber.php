<?php

namespace App\ChainCommandBundle\Event\Subscriber;

use App\ChainCommandBundle\Command\AbstractChildCommand;
use App\ChainCommandBundle\Command\AbstractParentCommand;
use App\ChainCommandBundle\Event\ChainCommandEvent;
use App\ChainCommandBundle\Event\CommandEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger) {}

    /**
     * Subscribe on events for logging
     *
     *
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            CommandEvents::INIT => 'onInit',
            CommandEvents::ADD_TO_CHAIN => 'onAddToChain',
            CommandEvents::EXECUTE => 'onExecute',
            CommandEvents::TERMINATE => 'onTerminate'
        ];
    }

    public function onInit(ChainCommandEvent $event): void
    {
        $command = $event->getCommand();
        if ($command instanceof AbstractParentCommand) {
            $this->logger->info(sprintf('%s is a master command of a command chain that has registered member commands', $command->getName()));
        } elseif ($command instanceof AbstractChildCommand) {
            $this->logger->info(sprintf('%s initialized as part of command chain', $command->getName()));
        }
    }

    public function onAddToChain(ChainCommandEvent $event): void
    {
        $command = $event->getCommand();
        $parentCommand = $command->getParent();
        if ($parentCommand) {
            $this->logger->info(sprintf('%s registered as a member of %s command chain', $command->getName(), $parentCommand->getName()));
        }
    }

    public function onExecute(ChainCommandEvent $event): void
    {
        $command = $event->getCommand();
        if ($command instanceof AbstractParentCommand) {
            $this->logger->info(sprintf('Executing %s command itself first:', $command->getName()));
        } elseif ($command instanceof AbstractChildCommand) {
            $this->logger->info(sprintf('Executing %s chain members:', $command->getParent()->getName()));
        }
    }

    public function onTerminate(ChainCommandEvent $event): void
    {
        $command = $event->getCommand();
        if ($command instanceof AbstractParentCommand) {
            $this->logger->info(sprintf('Execution of %s chain completed.', $command->getName()));
        } elseif ($command instanceof AbstractChildCommand) {
            $this->logger->info(sprintf('Execution of %s command completed.', $command->getName()));
        }
    }
}