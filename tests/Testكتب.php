<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\كتبController;
use App\Repository\كتبRepository;
use App\Entity\كتب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testكتب extends TestCase
{
    private $controller;
    private $repository;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(كتبRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->controller = new كتبController($this->repository, $this->router);
    }

    public function testGetAll()
    {
        $expectedResponse = new Response(json_encode(['books' => ['book1', 'book2']]));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn(['book1', 'book2']);

        $response = $this->controller->getAll($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $bookId = 1;
        $expectedResponse = new Response(json_encode(['book' => 'book1']));

        $this->repository->expects($this->once())
            ->method('find')
            ->with($bookId)
            ->willReturn('book1');

        $response = $this->controller->getOne($this->request, [$bookId]);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $book = new كتب();
        $book->setTitle('book1');
        $book->setDescription('description1');

        $expectedResponse = new Response(json_encode(['book' => $book]));

        $this->repository->expects($this->once())
            ->method('save')
            ->with($book)
            ->willReturn($book);

        $response = $this->controller->create($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $bookId = 1;
        $book = new كتب();
        $book->setTitle('book1');
        $book->setDescription('description1');

        $expectedResponse = new Response(json_encode(['book' => $book]));

        $this->repository->expects($this->once())
            ->method('find')
            ->with($bookId)
            ->willReturn($book);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($book)
            ->willReturn($book);

        $response = $this->controller->update($this->request, [$bookId]);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $bookId = 1;

        $this->repository->expects($this->once())
            ->method('find')
            ->with($bookId)
            ->willReturn(new كتب());

        $this->repository->expects($this->once())
            ->method('remove')
            ->with(new كتب());

        $response = $this->controller->delete($this->request, [$bookId]);
        $this->assertEquals(new Response('', Response::HTTP_NO_CONTENT), $response);
    }
}


Note: This code assumes that the `كتبController` class has methods `getAll`, `getOne`, `create`, `update`, and `delete` which handle the respective CRUD operations. The `كتبRepository` class has methods `findAll`, `find`, `save`, and `remove` which handle the database operations. The `كتب` entity class represents a book with `title` and `description` properties.