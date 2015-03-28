<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dic = [
    'di' => [
        'Mailer' => [
            'class' => 'Mailer',
        ],
        'MyCommandHandler' => [
            'class' => 'MyCommandHandler',
            'arguments' => [
                'Mailer',
            ],
        ],
    ],
];

$mapping = [
    'MyCommand' => 'MyCommandHandler',
];

final class Mailer
{
    public function __construct()
    {}
}

final class MyCommand implements \League\Tactician\Command
{
    public $name,
           $emailAddress;

    public function __construct(
        $name,
        $emailAddress
    ) {
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

$containerLocator = new League\Tactician\Container\ContainerLocator(
    new League\Container\Container($dic),
    $mapping
);

$handlerMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
    $containerLocator,
    new \League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector()
);

$commandBus = new \League\Tactician\CommandBus([$handlerMiddleware]);


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

