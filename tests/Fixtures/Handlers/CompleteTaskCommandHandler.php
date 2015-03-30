<?php

namespace Test\League\Tactician\Container\Fixtures\Handlers;

use Test\League\Tactician\Container\Fixtures\Container\Mailer;

class CompleteTaskCommandHandler
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handleCompleteTaskCommand($command)
    {
    }
}
