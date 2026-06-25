<?php

namespace App\Tests\Controller;

use App\Controller\تصنيفاتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Testتصنيفات extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new تصنيفاتController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetAll(): void
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM categories')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('GET');

        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO categories (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'Category Name');

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE categories SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('name', 'Updated Category Name');
        $request->attributes->set('id', 1);

        $response = $this->controller->update($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM categories WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('DELETE');
        $request->attributes->set('id', 1);

        $response = $this->controller->delete($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}