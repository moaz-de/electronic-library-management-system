<?php

namespace App\Tests\Controller;

use App\Controller\ArticlesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testمقالات extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->controller = new ArticlesController($this->pdo, $this->router, $this->tokenStorage);
    }

    public function testGetArticles()
    {
        $request = new Request();
        $request->attributes->set('page', 1);
        $request->attributes->set('limit', 10);

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مقالات LIMIT 10 OFFSET 0')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getArticles($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostArticle()
    {
        $request = new Request();
        $request->request->set('title', 'Article Title');
        $request->request->set('content', 'Article Content');

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مقالات (title, content) VALUES (:title, :content)')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $response = $this->controller->postArticle($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutArticle()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $request->request->set('title', 'Updated Article Title');
        $request->request->set('content', 'Updated Article Content');

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مقالات SET title = :title, content = :content WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->putArticle($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteArticle()
    {
        $request = new Request();
        $request->attributes->set('id', 1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مقالات WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->deleteArticle($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}