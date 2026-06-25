<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\بحاثونController;
use App\Repository\بحثونRepository;
use App\Service\بحثونService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Testبحثون extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(بحثونRepository::class);
        $this->service = $this->createMock(بحثونService::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new بحثونController($this->repository, $this->service, $this->pdo);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'بحث'],
                ['id' => 2, 'name' => 'بحث2'],
            ]);

        $response = $this->controller->getAll();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(['id' => $id, 'name' => 'بحث']);

        $response = $this->controller->getOne($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'بحث'];
        $this->service->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1, 'name' => 'بحث']);

        $response = $this->controller->create($data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'بحث2'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(['id' => $id, 'name' => 'بحث']);

        $this->service->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(['id' => $id, 'name' => 'بحث2']);

        $response = $this->controller->update($id, $data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(['id' => $id, 'name' => 'بحث']);

        $response = $this->controller->delete($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\بحوثونController.php

namespace App\Controller;

use App\Repository\بحثونRepository;
use App\Service\بحثونService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class بحثونController
{
    private $repository;
    private $service;
    private $pdo;

    public function __construct(بحثونRepository $repository, بحثونService $service, \PDO $pdo)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->pdo = $pdo;
    }

    /**
     * @Route("/بحوثون", methods={"GET"})
     */
    public function getAll()
    {
        return new JsonResponse($this->repository->findAll());
    }

    /**
     * @Route("/بحوثون/{id}", methods={"GET"})
     */
    public function getOne($id)
    {
        return new JsonResponse($this->repository->find($id));
    }

    /**
     * @Route("/بحوثون", methods={"POST"})
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return new JsonResponse($this->service->create($data));
    }

    /**
     * @Route("/بحوثون/{id}", methods={"PUT"})
     */
    public function update($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return new JsonResponse($this->service->update($id, $data));
    }

    /**
     * @Route("/بحوثون/{id}", methods={"DELETE"})
     */
    public function delete($id)
    {
        $this->repository->find($id);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\بحثونRepository.php

namespace App\Repository;

use App\Entity\بحثون;

interface بحثونRepository
{
    public function findAll();
    public function find($id);
}



// App\Service\بحثونService.php

namespace App\Service;

use App\Entity\بحثون;

class بحثونService
{
    public function create($data)
    {
        // Create logic here
    }

    public function update($id, $data)
    {
        // Update logic here
    }
}



// App\Entity\بحثون.php

namespace App\Entity;

class بحثون
{
    private $id;
    private $name;

    // Getters and setters
}