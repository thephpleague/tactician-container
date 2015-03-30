<?php

namespace Test\League\Tactician\Container;

use League\Container\Container;
use League\Tactician\Container\ContainerLocator;
use PHPUnit_Framework_TestCase;
use Test\League\Tactician\Container\Fixtures\Commands\CompleteTaskCommand;
use Test\League\Tactician\Container\Fixtures\Commands\DeleteTaskCommand;
use Test\League\Tactician\Container\Fixtures\Commands\UpdateProfileCommand;
use Test\League\Tactician\Container\Fixtures\Container\Mailer;
use Test\League\Tactician\Container\Fixtures\Handlers\CompleteTaskCommandHandler;

class ContainerLocatorTest extends PHPUnit_Framework_TestCase
{
    /** @var  ContainerLocator */
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

    /**
     * @expectedException League\Tactician\Container\Exception\MissingCommandException
     */
    public function testEmptyArrayPassedToConstructor()
    {
        $containerLocator = new ContainerLocator(
            new Container([]),
            []
        );

        $containerLocator->getHandlerForCommand(new CompleteTaskCommand());
    }

    /**
     * @codeCoverage League\Tactician\Container::getHandlerForCommand
     */
    public function testHandlerIsReturnedForSpecificClass()
    {
        $this
            ->assertInstanceOf(
                CompleteTaskCommandHandler::class,
                $this->containerLocator->getHandlerForCommand(new CompleteTaskCommand)
            );
    }

    /**
     * @codeCoverage League\Tactician\Container::getHandlerForCommand
     * @codeCoverage League\Tactician\Container\Exception\MissingCommandException
     *
     * @expectedException League\Tactician\Container\Exception\MissingCommandException
     */
    public function testMissingCommandException()
    {
        $this->containerLocator->getHandlerForCommand(new DeleteTaskCommand());
    }
}
