<?php

namespace League\Tactician\Container;

use Psr\Container\ContainerInterface;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;

/**
 * Fetch handler instances from an in-memory collection.
 *
 * This locator allows you to bind a handler FQCN to receive commands of a
 * certain command name.
 */
class ContainerLocator implements HandlerLocator
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * The collection of Command/CommandHandler
     *
     * @var array
     */
    protected $commandNameToHandlerMap = [];

    /**
     * @param ContainerInterface $container
     * @param array              $commandNameToHandlerMap
     */
    public function __construct(
        ContainerInterface $container,
        array $commandNameToHandlerMap = []
    ) {
        $this->container = $container;
        $this->addHandlers($commandNameToHandlerMap);
    }

    /**
     * Bind a handler instance to receive all commands with a certain class
     *
     * @param string $handler     Handler to receive class
     * @param string $commandName Can be a class name or name of a NamedCommand
     */
    public function addHandler($handler, $commandName)
    {
        $this->commandNameToHandlerMap[$commandName] = $handler;
    }

    /**
     * Allows you to add multiple handlers at once.
     *
     * The map should be an array in the format of:
     *  [
     *      'AddTaskCommand'      => 'AddTaskCommandHandler',
     *      'CompleteTaskCommand' => 'CompleteTaskCommandHandler',
     *  ]
     *
     * @param array $commandNameToHandlerMap
     */
    public function addHandlers(array $commandNameToHandlerMap)
    {
        foreach ($commandNameToHandlerMap as $commandName => $handler) {
            $this->addHandler($handler, $commandName);
        }
    }

    /**
     * Retrieves the handler for a specified command
     *
     * @param string $commandName
     *
     * @return object
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        if (!isset($this->commandNameToHandlerMap[$commandName])) {
            throw MissingHandlerException::forCommand($commandName);
        }

        $serviceId = $this->commandNameToHandlerMap[$commandName];

        return $this->container->get($serviceId);
    }
}
