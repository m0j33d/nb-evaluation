<?php

namespace App\Application\Command;

class CreateUserCommand
{
    public function __construct(private array $params)
    {
        //
    }

    public function getParams(): array
    {
        return $this->params;
    }
}