<?php

namespace App\Tests\Feature;

use App\DTO\SignUpDTO;
use App\Service\UserService;
use App\Tests\TestHelper;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LogoutTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $application = new Application(self::$kernel);
        TestHelper::setup($application);
        $container = static::getContainer();
        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        $signUpDto = new SignUpDTO(
            'test4@test.test',
            '11111111',
            '11111111'
        );

        try {
            $userService->register($signUpDto);
        } catch (Exception) {

        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        $client = static::createClient();
        $application = new Application($client->getKernel());
        TestHelper::down($application);
    }

    public function testLogout()
    {
        // Авторизация перед logout
        $this->client->request('POST', '/api/auth/signin', [
            'email' => 'test4@test.test',
            'password' => '11111111',
        ]);

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];
        $this->assertArrayHasKey('token', $data);

        $token = $data['token'];

        // Отправка запроса logout
        $this->client->request('POST', '/api/auth/logout', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetUserAfterLogout()
    {
        // Авторизация перед logout
        $this->client->request('POST', '/api/auth/signin', [
            'email' => 'test4@test.test',
            'password' => '11111111',
        ]);

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];
        $this->assertArrayHasKey('token', $data);

        $token = $data['token'];

        // Logout
        $this->client->request('POST', '/api/auth/logout', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Попытка получить пользователя после logout
        $this->client->request('GET', '/api/user/me', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
