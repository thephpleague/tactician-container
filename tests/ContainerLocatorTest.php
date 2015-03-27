<?php

namespace Test\League\Tactician\Container;

use League\Container\Container;
use League\Tactician\Container\ContainerLocator;
use PHPUnit_Framework_TestCase;
use Test\League\Tactician\Container\Fixtures\Commands\CompleteTaskCommand;
use Test\League\Tactician\Container\Fixtures\Commands\UpdateProfileCommand;

class ContainerLocatorTest extends PHPUnit_Framework_TestCase
{
    /** @var  ContainerLocator */
    private $containerLocator;

    protected function setUp()
    {
        $dic = [
            'di' => [
                'Mailer' => [
                    'class' => 'Test\League\Tactician\Container\Fixtures\Container\Mailer',
                ],
                'NonExistentDependancy' => [
                    'class' => 'A\Made\Up\Namespace',
                ],
                'CompleteTaskCommandHandler' => [
                    'class' => 'Test\League\Tactician\Container\Fixtures\Command\CompleteTaskCommand',
                    'arguments' => [
                        'Mailer',
                    ],
                ],
            ],
        ];

        $mapping = [
            'Test\League\Tactician\Container\Fixtures\Commands\CompleteTaskCommand' =>
                'Test\League\Tactician\Container\Fixtures\Handlers\CompleteTaskCommandHandler',
        ];

        $this->containerLocator = new ContainerLocator(
            new Container($dic),
            $mapping
        );
    }

    public function testHandlerIsReturnedForSpecificClass()
    {
        $this
            ->assertInstanceOf(
                'Test\League\Tactician\Container\Fixtures\Handlers\CompleteTaskCommandHandler',
                $this->containerLocator->getHandlerForCommand(new CompleteTaskCommand)
            );
    }
}
