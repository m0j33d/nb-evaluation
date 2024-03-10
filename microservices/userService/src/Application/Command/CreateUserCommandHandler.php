<?php

namespace App\Application\Command;

use App\Domain\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Validators\CreateUserValidator;
use App\Application\Event\UserCreatedEvent;



#[AsMessageHandler]
class CreateUserCommandHandler
{
    public function __construct(
        private UserRepository      $userRepository,
        private MessageBusInterface $messageBus,
        private CreateUserValidator $validator
    )
    {
        //
    }

    public function __invoke(CreateUserCommand $command): array
    {
        $params = $command->getParams();

        $this->validator->validate($params);

        $user = $this->userRepository->createUser($params);

        $event = new UserCreatedEvent($user->toArray());

        $this->messageBus->dispatch($event);

        return [
            'message' => 'User Created',
            'user' => $user->toArray()
        ];
    }
}