<?php


namespace App\ChainCommandBundle\Event;


class CommandEvents
{
    public const INIT = 'chain_command.init';
    public const ADD_TO_CHAIN = 'chain_command.add';
    public const EXECUTE = 'chain_command.execute';
    public const TERMINATE = 'chain_command.terminate';
}