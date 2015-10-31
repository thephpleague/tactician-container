<?php

namespace League\Tactician\Container;

use League\Container\Container;
use League\Container\ReflectionContainer;
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

        $container = new Container;
        $container->delegate(
            new ReflectionContainer
        );
        $container->add('Mailer', Mailer::class);
        $container->add('CompleteTaskCommandHandler', CompleteTaskCommand::class)
                  ->withArgument('Mailer');

        $mapping = [
            CompleteTaskCommand::class => CompleteTaskCommandHandler::class,
        ];

        $this->containerLocator = new ContainerLocator(
            $container,
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

    public function testHandlerIsReturnedForAddedClass()
    {
        $this->containerLocator->addHandler('stdClass', CompleteTaskCommand::class);
        $this->assertInstanceOf(
            'stdClass',
            $this->containerLocator->getHandlerForCommand(CompleteTaskCommand::class)
        );
    }

    public function testHandlerIsReturnedForAddedClasses()
    {
        $this->containerLocator->addHandlers([CompleteTaskCommand::class => 'stdClass']);
        $this->assertInstanceOf(
            'stdClass',
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
