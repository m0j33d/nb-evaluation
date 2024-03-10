<?php

namespace App\Application\Event;

class UserCreatedEvent
{
    public function __construct(private array $data)
    {
        //
    }

    public function getData(): array
    {
        return $this->data;
    }
}