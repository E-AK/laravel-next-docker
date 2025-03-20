<?php

namespace App\Tests\Feature;

use App\Enums\TaskStatus;
use App\Tests\BaseTest;
use Symfony\Component\Uid\Uuid;

class CreateTaskTest extends BaseTest
{
    public function testSuccessCreateTask()
    {
        $userId = Uuid::v4()->toString();
        $this->client->setServerParameter('HTTP_X-User-ID', $userId);
        $this->client->request('POST', '/api/tasks/create', [
            'text' => '11111111',
        ]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];

        $this->assertSame($data['text'], '11111111');
        $this->assertSame($data['status'], TaskStatus::TODO->value);
        $this->assertSame($data['user_id'], $userId);
    }

    public function testFailCreateTaskWithoutUserId()
    {
        $this->client->request('POST', '/api/tasks/create', [
            'text' => '11111111',
        ]);

        $response = $this->client->getResponse();

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testFailCreateTaskWithoutText()
    {
        $this->client->request('POST', '/api/tasks/create', [
            'text' => ''
        ]);

        $response = $this->client->getResponse();
        $this->assertEquals(422, $response->getStatusCode());
    }
}