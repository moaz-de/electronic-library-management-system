<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Call;
use PHPUnit\Framework\MockObject\MockObject as MockObjectAlias;
use PHPUnit\Framework\MockObject\MockBuilder as MockBuilderAlias;
use PHPUnit\Framework\MockObject\MockObject as MockObjectAlias2;
use PHPUnit\Framework\MockObject\MockBuilder as MockBuilderAlias2;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->authRepository->expects($this->once())
            ->method('verifyPassword')
            ->with(new User($username, $password), $password)
            ->willReturn(true);

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $email = 'test@example.com';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('createUser')
            ->with(new User($username, $password, $email))
            ->willReturn(new User($username, $password, $email));

        $this->authService->register($username, $password, $email);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailureUsernameTaken()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $email = 'test@example.com';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, 'password', 'email'));

        $this->authService->register($username, $password, $email);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterFailureEmailTaken()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $email = 'test@example.com';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn(new User('username', 'password', $email));

        $this->authService->register($username, $password, $email);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

1. Successful login with correct credentials.
2. Failed login with incorrect credentials.
3. Successful registration with new username and email.
4. Failed registration due to username already taken.
5. Failed registration due to email already taken.

Each test method uses the `createMock` method to create a mock object for the `AuthRepository` class, which is then used to set up the expected behavior for the `getUserByUsername`, `getUserByEmail`, and `createUser` methods.

The `testLoginSuccess` and `testRegisterSuccess` methods test the successful login and registration scenarios, respectively. The `testLoginFailure` and `testRegisterFailure` methods test the failed login and registration scenarios, respectively.

The `assertEquals` and `assertTrue` assertions are used to verify that the expected behavior is achieved.