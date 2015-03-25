<?php

namespace League\Tactician\Container\Exception;

use League\Tactician\Command;
use League\Tactician\Exception\Exception;

/**
 * Command has not been added to the mapping array.
 */
class MissingCommandException extends \OutOfBoundsException implements Exception
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
        $exception = new static(
            sprintf(
                'The command %s has not been addeded to the mapping configuration.',
                get_class($command)
            )
        );
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
