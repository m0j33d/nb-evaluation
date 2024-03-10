<?php

namespace App\Tests\Functional\Infrastructure\Controller\User;

use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $kernel = static::bootKernel();

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,
            '--env' => 'test',
        ]));

        $application->run(new ArrayInput([
            'command' => 'doctrine:database:create',
            '--env' => 'test',
        ]));

        $application->run(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
            '--env' => 'test',
        ]));
    }


    public function testUserCreation(): void
    {
         $data = [
             'email' => 'johnny1@example.com',
             'firstName' => 'John',
             'lastName' => 'Doe',
         ];

         $this->client->request('POST', '/users', [], [], [], json_encode($data));

         $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

         $responseData = json_decode($this->client->getResponse()->getContent(), true);

         $this->assertArrayHasKey('message', $responseData);
         $this->assertArrayHasKey('user', $responseData);

    }
}
