<?php

namespace App\BarBundle\Command;

use App\ChainCommandBundle\Command\AbstractChildCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BarHelloCommand extends AbstractChildCommand
{
    protected static $defaultName = 'bar:hello';

    protected function configure(): void
    {
        $this->setDescription('Chain Bar');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Chain Bar!');
        return Command::SUCCESS;
    }
}