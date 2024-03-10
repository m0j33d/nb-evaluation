<?php

namespace App\Infrastructure\Controller\User;

use App\Application\Command\CreateUserCommand;
use App\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;;

class UserController extends BaseController
{
    protected const COMMAND = CreateUserCommand::class;

    public function mapRequestToCommand(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        //check if the decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) throw new \RuntimeException('JSON decoding error.');

        return [
            'email' => $data['email'] ?? null,
            'firstName' => $data['firstName'] ?? null,
            'lastName' => $data['lastName'] ?? null,
        ];
    }
}