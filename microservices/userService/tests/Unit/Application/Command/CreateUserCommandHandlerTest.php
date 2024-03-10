<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\CreateUserCommand;
use App\Application\Command\CreateUserCommandHandler;
use App\Application\Event\UserCreatedEvent;
use App\Application\Validators\CreateUserValidator;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserCommandHandlerTest extends TestCase
{
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;
    private CreateUserValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->validator = $this->createMock(CreateUserValidator::class);
    }

    /**
     * @throws Exception
     */
    public function testInvokeUserCreationCommand(): void
    {
        $params = [
            'firstName' => 'Johnny',
            'lastName' => 'Doe',
            'email' => 'johnny@example.com'
        ];

        $command = new CreateUserCommand($params);

        $handler = new CreateUserCommandHandler(
            $this->userRepository,
            $this->messageBus,
            $this->validator
        );

        $user = $this->createMock(User::class);

        $this->userRepository
            ->expects($this->once())
            ->method('createUser')
            ->with($params)
            ->willReturn($user);

        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(UserCreatedEvent::class))
            ->willReturnCallback(function ($event) {
                return new Envelope($event);
            });

        $result = $handler->__invoke($command);

        $this->assertArrayHasKey('message', $result);
    }
}
