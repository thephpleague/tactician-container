<?php

namespace League\Tactician\Container\Test\Fixtures\Handlers;

use League\Tactician\Container\Test\Fixtures\Container\Mailer;

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
