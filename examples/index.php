<?php

require_once __DIR__ . '/../vendor/autoload.php';

use League\Container\ReflectionContainer;
use League\Tactician\Container\ContainerLocator;
use League\Container\Container;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\CommandBus;

$mapping = [
    'MyCommand' => 'MyCommandHandler',
];

final class Mailer
{
}

final class MyCommand
{
    public $name;
    public $emailAddress;

    public function __construct($name, $emailAddress)
    {
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
    }
}

final class MyCommandHandler
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handleMyCommand($command)
    {
        $format = <<<MSG
Hi %s,

Your email address is %s.

--
Cheers
MSG;
        echo sprintf($format, $command->name, $command->emailAddress);
    }
}

$containerLocator = new ContainerLocator(
    (new Container())->delegate(new ReflectionContainer()),
    $mapping
);

$handlerMiddleware = new CommandHandlerMiddleware(
    new ClassNameExtractor(),
    $containerLocator,
    new HandleClassNameInflector()
);

$commandBus = new CommandBus([$handlerMiddleware]);


$command = new MyCommand('Joe Bloggs', 'j.bloggs@theinternet.com');
echo '<pre>';
try {
    $commandBus->handle($command);
} catch (\Exception $e) {
    echo $e->getMessage();
    echo '<pre>';
    print_r($e->getTraceAsString());
    echo '</pre>';
}

