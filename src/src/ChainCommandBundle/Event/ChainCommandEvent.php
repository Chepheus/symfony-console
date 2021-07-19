<?php

namespace App\ChainCommandBundle\Event;

use Symfony\Component\Console\Command\Command;
use Symfony\Contracts\EventDispatcher\Event;

class ChainCommandEvent extends Event
{
    public function __construct(private Command $command) {}

    public function getCommand(): Command
    {
        return $this->command;
    }
}