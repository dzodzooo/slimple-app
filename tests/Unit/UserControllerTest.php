<?php
declare(strict_types=1);
namespace Tests\Unit;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use \App\Session;
use \App\Controller\UserController;
use \App\Contracts\SessionInterface;
use \App\Exception\ValidationException;
use \App\Services\AuthService;
use \App\Services\LoginValidator;
use \App\Services\RegistrationValidator;
use \Tests\Unit\Services\UserRepoMockup;

class UserControllerTest extends TestCase
{
    protected SessionInterface $session;
    protected UserController $userController;
    protected ResponseInterface $response;
    protected ServerRequestInterface $request;
    protected function setUp(): void
    {
        $twigMock = $this->createStub(\Twig\Environment::class);

        $userRepo = new UserRepoMockup();
        $userRepo->init();
        $authService = new AuthService($userRepo);
        $registrationValidator = new RegistrationValidator();
        $login = new LoginValidator();
        $this->session = new Session();

        $this->userController = new UserController($twigMock, $this->session, $authService, $registrationValidator, $login);

        $requestFactory = new ServerRequestFactory();
        $this->request = $requestFactory
            ->createServerRequest('POST', '/register')
            ->withHeader('Content-Type', 'application/json');

        $responseFactory = new ResponseFactory();
        $this->response = $responseFactory->createResponse();
    }
    protected function tearDown(): void
    {
        unset($this->userController);
        unset($this->response);
        unset($this->request);
        unset($this->session);
    }

    public function testCanRegisterNewAccount()
    {
        $userData = ['email' => 'user33@gmail.com', 'password' => 'user33password', 'name' => 'user33', 'confirmPassword' => 'user33password'];

        $request = $this->request->withParsedBody($userData);

        $actualResponse = $this->userController->register($request, $this->response);

        $this->assertSame(StatusCodeInterface::STATUS_FOUND, $actualResponse->getStatusCode());
    }

    public function testRegistrationFailsWithMismatchedPasswords()
    {
        $userData = ['email' => 'user33@gmail.com', 'password' => 'user33password', 'name' => 'user33', 'confirmPassword' => 'notsameaspassword'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->register($request, $this->response);
    }
    public function testRegistrationFailsWithTakenEmail()
    {
        $userData = ['email' => 'user1@gmail.com', 'password' => 'user33password', 'name' => 'user33', 'confirmPassword' => 'notsameaspassword'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->register($request, $this->response);
    }
    public function testRegistrationFailureDoesntClearFormInput()
    {
        $this->assertNull($this->session->get('email'));
        $this->assertNull($this->session->get('name'));
        $this->assertNull($this->session->get('password'));
        $this->assertNull($this->session->get('confirmPassword'));

        $userData = ['email' => 'user33@gmail.com', 'password' => 'user33password', 'name' => 'user33', 'confirmPassword' => 'notsameaspassword'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->register($request, $this->response);

        $this->assertNotNull($this->session->get('email'));
        $this->assertNotNull($this->session->get('name'));
        $this->assertNull($this->session->get('password'));
        $this->assertNull($this->session->get('confirmPassword'));
    }

    public function testCanLogin()
    {
        $userData = ['email' => 'user1@gmail.com', 'password' => 'user1password'];

        $request = $this->request->withParsedBody($userData);

        $actualResponse = $this->userController->login($request, $this->response);

        $this->assertSame(StatusCodeInterface::STATUS_FOUND, $actualResponse->getStatusCode());
    }

    public function testLoginFailsWithWrongPassword()
    {
        $userData = ['email' => 'user1@gmail.com', 'password' => 'notgood'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->login($request, $this->response);
    }

    public function testLoginFailsWithWrongEmail()
    {
        $userData = ['email' => 'user90@gmail.com', 'password' => 'user1password'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->login($request, $this->response);
    }
    public function testLoginFailsWithInvalidEmailFormat()
    {
        $userData = ['email' => 'user1gmail.com', 'password' => 'user1password'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->login($request, $this->response);
    }
    public function testLoginFailureDoesntClearFormInput()
    {
        $this->assertNull($this->session->get('email'));
        $this->assertNull($this->session->get('password'));

        $userData = ['email' => 'user1gmail.com', 'password' => 'user1password'];

        $request = $this->request->withParsedBody($userData);

        $this->expectException(ValidationException::class);

        $this->userController->login($request, $this->response);

        $this->assertNotNull($this->session->get('email'));
        $this->assertNull($this->session->get('password'));
    }

}