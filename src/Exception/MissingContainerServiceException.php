<?php

namespace League\Tactician\Container\Exception;

use League\Tactician\Command;
use League\Tactician\Exception\Exception;

/**
 * No Container Service could be found for the given command.
 */
class MissingContainerServiceException extends \OutOfBoundsException implements Exception
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @param Command $command
     *
     * @return static
     */
    public static function forCommand(Command $command)
    {
        $exception = new static('Missing container service for command: ' . get_class($command));
        $exception->command = $command;

        return $exception;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
