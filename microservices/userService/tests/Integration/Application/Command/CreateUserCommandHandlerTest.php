<?php

namespace App\Tests\Integration\Application\Command;

use App\Application\Command\CreateUserCommand;
use App\Application\Command\CreateUserCommandHandler;
use App\Application\Event\UserCreatedEvent;
use App\Application\Validators\CreateUserValidator;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class CreateUserCommandHandlerTest extends KernelTestCase
{
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;
    private CreateUserValidator $validator;
    
    protected function setUp(): void
    {
        self::bootKernel();

        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->messageBus = new TraceableMessageBus(self::getContainer()->get(MessageBusInterface::class));
        $this->validator = self::getContainer()->get(CreateUserValidator::class);

        $entityManager = self::getContainer()->get('doctrine.orm.default_entity_manager');

        $schemaTool = new SchemaTool($entityManager);

        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->updateSchema($metadata);
    }

    public function testInvokeCommandHandler(): void
    {
        $handler = new CreateUserCommandHandler($this->userRepository, $this->messageBus, $this->validator);

        $data = [
            'firstName' => 'Johnny',
            'lastName' => 'Doe',
            'email' => 'johnny@example.com'
        ];

        $command = new CreateUserCommand($data);

        $result = $handler($command);

        $this->assertArrayHasKey('message', $result);

        $dispatchedMessages = $this->messageBus->getDispatchedMessages();
        $this->assertCount(1, $dispatchedMessages);
        $this->assertInstanceOf(UserCreatedEvent::class, $dispatchedMessages[0]['message']);
    }
}
