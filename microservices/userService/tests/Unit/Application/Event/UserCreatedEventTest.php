<?php

namespace App\Tests\Unit\Application\Event;

use App\Application\Event\UserCreatedEvent;
use PHPUnit\Framework\TestCase;

class UserCreatedEventTest extends TestCase
{
    public function testGettersReturnCorrectData(): void
    {
        $event = new UserCreatedEvent([
            'id' => 1,
            'firstName' => 'Johnny',
            'lastName' => 'Doe',
            'email' => 'johnny@example.com'
        ]);

        $this->assertEquals(1, $event->getData()['id']);
        $this->assertEquals('Johnny', $event->getData()['firstName']);
        $this->assertEquals('Doe', $event->getData()['lastName']);
        $this->assertEquals('johnny@example.com', $event->getData()['email']);
        $this->assertIsArray($event->getData());
    }
}
