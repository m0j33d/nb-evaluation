<?php

namespace App\Tests\Unit\Application\Event;

use PHPUnit\Framework\TestCase;
use App\Application\Event\UserCreatedEvent;

class UserCreatedEventTest extends TestCase
{
    public function testGetUserData(): void
    {
        $data = [
            'id' => 1,
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'doe@example.com'
        ];
        
        $event = new UserCreatedEvent($data);

        $this->assertSame($data, $event->getData());
    }
}
