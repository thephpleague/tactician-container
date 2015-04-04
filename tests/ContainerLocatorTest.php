<?php

namespace League\Tactician\Container;

use League\Container\Container;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Container\Test\Fixtures\Commands\CompleteTaskCommand;
use League\Tactician\Container\Test\Fixtures\Commands\DeleteTaskCommand;
use League\Tactician\Container\Test\Fixtures\Container\Mailer;
use League\Tactician\Container\Test\Fixtures\Handlers\CompleteTaskCommandHandler;

class ContainerLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerLocator
     */
    private $containerLocator;

    protected function setUp()
    {
        $dic = [
            'di' => [
                'Mailer' => [
                    'class' => Mailer::class,
                ],
                'CompleteTaskCommandHandler' => [
                    'class' => CompleteTaskCommand::class,
                    'arguments' => [
                        'Mailer',
                    ],
                ],
            ],
        ];

        $mapping = [
            CompleteTaskCommand::class => CompleteTaskCommandHandler::class,
        ];

        $this->containerLocator = new ContainerLocator(
            new Container($dic),
            $mapping
        );
    }

    public function testHandlerIsReturnedForSpecificClass()
    {
        $this->assertInstanceOf(
            CompleteTaskCommandHandler::class,
            $this->containerLocator->getHandlerForCommand(CompleteTaskCommand::class)
        );
    }

    /**
     * @expectedException League\Tactician\Exception\MissingHandlerException
     */
    public function testMissingCommandException()
    {
        $this->containerLocator->getHandlerForCommand(DeleteTaskCommand::class);
    }
}
