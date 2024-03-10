<?php

namespace App\Tests\Unit\Application\Listener;

use App\Application\Event\UserCreatedEvent;
use App\Application\Listener\UserCreatedListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class UserCreatedListenerTest extends TestCase
{
    private string $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = sys_get_temp_dir();
        
        mkdir($this->projectDir . '/var/log', 0777, true);
    }

    public function testInvokeListener(): void
    {
        $data = [
            'id' => 1,
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johnnydoe@example.com'
        ];
        
        $event = new UserCreatedEvent($data);

        $params = new ParameterBag(['kernel.project_dir' => $this->projectDir]);
        $listener = new UserCreatedListener($params);

        $listener($event);

        $logPath = $this->projectDir . '/var/log/users.log';
        $this->assertFileExists($logPath);
        $this->assertStringContainsString(json_encode($data, JSON_PRETTY_PRINT), file_get_contents($logPath));
    }

    protected function tearDown(): void
    {
        @unlink($this->projectDir . '/var/log/user.log');
        @rmdir($this->projectDir . '/var/log');
    }
}
