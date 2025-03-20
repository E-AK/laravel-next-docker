<?php

namespace App\Tests\Feature;

use App\Entity\Task;
use App\Enums\TaskStatus;
use App\Tests\BaseTest;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Symfony\Component\Uid\Uuid;

class UpdateTaskTest extends BaseTest
{
    public function createTask(): Task
    {
        $userId = Uuid::v4()->toString();
        $this->client->setServerParameter('HTTP_X-User-ID', $userId);
        $this->client->request('POST', '/api/tasks/create', [
            'text' => '11111111',
        ]);

        $response = $this->client->getResponse();

        return $this->taskFromResponse($response, $userId);
    }

    public function testSuccessUpdateText()
    {
        $task = $this->createTask();
        $this->client->setServerParameter('HTTP_Content-Type', 'application/json');
        $this->client->request(
            'PATCH',
            "/api/tasks/edit/{$task->getId()->toString()}",
            [
                'text' => 'updated',
            ]
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];

        $this->assertSame($data['text'], 'updated');
        $this->assertSame($data['status'], $task->getStatus()->value);
        $this->assertSame($data['user_id'], $task->getUserId()->toString());
    }

    public function testFailUpdateTextWithEmptyText()
    {
        $task = $this->createTask();
        $this->client->setServerParameter('HTTP_Content-Type', 'application/json');
        $this->client->request(
            'PATCH',
            "/api/tasks/edit/{$task->getId()->toString()}",
            [
                'text' => ''
            ]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testSuccessUpdateStatus()
    {
        $task = $this->createAndChangeStatus();

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];

        $this->assertSame($data['status'], TaskStatus::DOES->value);
        $this->assertSame($data['user_id'], $task->getUserId()->toString());
    }

    public function testNonChangedCreatedAt()
    {
        $task = $this->createAndChangeStatus();

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];

        $this->assertSame(
            $task->getCreatedAt()->format('Y-m-d H:i:s'),
            (new DateTimeImmutable(
                $data['created_at']['date'],
                new DateTimeZone($data['created_at']['timezone'])
            ))->format('Y-m-d H:i:s')
        );
    }

    public function testChangedUpdatedAt()
    {
        $task = $this->createAndChangeStatus();

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true)['data'];

        $this->assertNotSame(
            $task->getUpdatedAt(),
            new DateTimeImmutable(
                $data['updated_at']['date'],
                new DateTimeZone($data['updated_at']['timezone'])
            )
        );
    }

    /**
     * @return Task
     * @throws Exception
     */
    public function createAndChangeStatus(): Task
    {
        $task = $this->createTask();
        $this->client->request(
            'PATCH',
            "/api/tasks/status/{$task->getId()->toString()}"
        );
        $response = $this->client->getResponse();

        return $this->taskFromResponse($response, $task->getUserId()->toString());
    }

    /**
     * @param  object  $response
     * @param  string  $userId
     *
     * @return Task
     * @throws Exception
     */
    public function taskFromResponse(
        object $response,
        string $userId
    ): Task {
        $data = json_decode($response->getContent(), true)['data'];

        $task = new Task();
        $task = $task->setId(new Uuid($data['id']));
        $task = $task->setUserId(new Uuid($userId)
        );
        $task = $task->setText($data['text']);
        $task = $task->setStatus(TaskStatus::TODO);
        $task = $task->setCreatedAt(
            new DateTimeImmutable(
                $data['created_at']['date'],
                new DateTimeZone($data['created_at']['timezone']),
            )
        );
        $task = $task->setUpdatedAt(
            new DateTimeImmutable(
                $data['updated_at']['date'],
                new DateTimeZone($data['updated_at']['timezone']),
            )
        );

        return $task;
    }
}