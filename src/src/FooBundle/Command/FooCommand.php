<?php

namespace App\FooBundle\Command;

use App\BarBundle\Command\BarHelloCommand;
use App\ChainCommandBundle\Command\AbstractParentCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends AbstractParentCommand
{
    protected static $defaultName = 'foo:hello';

    /**
     * Set list of child commands
     *
     * @return string[]
     */
    public static function getChildCommands(): array
    {
        return [
            BarHelloCommand::class
        ];
    }

    protected function configure(): void
    {
        $this->setDescription('Chain Foo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Chain Foo!');
        return Command::SUCCESS;
    }
}