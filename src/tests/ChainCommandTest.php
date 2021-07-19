<?php

namespace App\Tests;

use App\BarBundle\Command\BarHelloCommand;
use App\ChainCommandBundle\Event\Listener\CommandListener;
use App\ChainCommandBundle\Exception\ChainPartException;
use App\FooBundle\Command\FooCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ChainCommandTest extends KernelTestCase
{
    private EventDispatcher $dispatcher;

    public function setUp(): void
    {
        static::bootKernel();
        $this->app = new Application(static::$kernel);
        $this->app->setAutoExit(false);
        $this->dispatcher  = new EventDispatcher;
    }

    /**
     * Test for positive case with default behaviour
     *
     * @throws \Exception
     */
    public function testPositiveCommand()
    {
        $fooCommand = new FooCommand(eventDispatcher: $this->dispatcher);
        $bufferedOutput = new BufferedOutput();

        /** @var Command $fooCommand */
        $exitCode = $fooCommand->run(new ArrayInput([]), $bufferedOutput);
        self::assertEquals("Chain Foo!\n", $bufferedOutput->fetch());

        self::assertEquals(Command::SUCCESS, $exitCode);
    }

    /**
     * Test for negative behaviour with exception
     *
     * @throws ChainPartException
     */
    public function testNegativeCommand()
    {
        $fooCommand = new FooCommand(eventDispatcher: $this->dispatcher);
        $barHelloCommand = new BarHelloCommand(eventDispatcher: $this->dispatcher);
        $barHelloCommand->setParent($fooCommand);

        $listener = new CommandListener(eventDispatcher: $this->dispatcher);

        $this->expectException(ChainPartException::class);
        $listener->onConsoleCommand(new ConsoleEvent($barHelloCommand, new ArrayInput([]), new ConsoleOutput()));
    }
}