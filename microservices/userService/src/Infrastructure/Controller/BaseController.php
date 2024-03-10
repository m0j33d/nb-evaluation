<?php

namespace App\Infrastructure\Controller;

use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected const COMMAND = '';

    abstract public function mapRequestToCommand(Request $request): array;

    public function __construct(protected readonly MessageBusInterface $messageBus)
    {
        //
    }

    public function __invoke(Request $request): Response
    {
        $commandClass = static::COMMAND;

        if (!class_exists($commandClass)) throw new LogicException('Command does not exist.');

        $envelope = $this
            ->messageBus
            ->dispatch(
                new $commandClass($this->mapRequestToCommand($request))
            );

        $handledStamp = $envelope->last(HandledStamp::class);

        if (is_null($handledStamp)) throw new LogicException('Command handler not found.');

        return $this->json($handledStamp->getResult());
    }
}