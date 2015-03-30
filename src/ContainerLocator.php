<?php

namespace League\Tactician\Container;

use League\Container\Container;
use League\Tactician\Container\Exception\MissingContainerServiceException;
use League\Tactician\Command;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;

/**
 * Fetch handler instances from an in-memory collection.
 *
 * This locator allows you to bind a handler fqcn to receive commands of a
 * certain class name.
 */
class ContainerLocator implements HandlerLocator
{
    /**
     * The Container object
     *
     * @var Container
     */
    protected $container;

    /**
     * The collection of Command/CommandHandler
     *
     * @var array
     */
    protected $commandToHandlerIdMap = [];

    /**
     * Class constructor
     *
     * @param Container $container                The Container object
     * @param array     $commandClassToHandlerMap The Command/Handler mapping
     */
    public function __construct(
        Container $container,
        array $commandClassToHandlerMap = []
    ) {
        $this->container = $container;
        $this->addHandlers($commandClassToHandlerMap);
    }

    /**
     * Bind a handler instance to receive all commands with a certain class
     *
     * @param string $handlerId        Handler to receive class
     * @param string $commandClassName Command class e.g. "My\TaskAddedCommand"
     */
    public function addHandler($handlerId, $commandClassName)
    {
        $this->commandToHandlerIdMap[$commandClassName] = $handlerId;
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
     * @param array $commandClassToHandlerMap The Command/Handler mapping
     */
    protected function addHandlers(array $commandClassToHandlerMap)
    {
        foreach ($commandClassToHandlerMap as $commandClass => $handler) {
            $this->addHandler($handler, $commandClass);
        }
    }

    /**
     * Retrieve handler for the given command
     *
     * @param Command $command The command object
     * @return Object
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand(Command $command)
    {
        $className = get_class($command);

        if (!isset($this->commandToHandlerIdMap[$className])) {
            throw MissingHandlerException::forCommand($command);
        }

        $serviceId = $this->commandToHandlerIdMap[$className];

        return $this->container->get($serviceId);
    }
}
