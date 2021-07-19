<?php


namespace App\ChainCommandBundle\TagInterfaces;


interface ParentCommandInterface
{
    /**
     * @return string[]
     */
    public static function getChildCommands(): array;
}