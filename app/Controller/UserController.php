<?php
declare(strict_types=1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Fig\Http\Message\StatusCodeInterface;

use \App\Contracts\AuthInterface;
use \App\Contracts\SessionInterface;
use \App\Exception\ValidationException;
use \App\Validator\LoginValidator;
use \App\Validator\RegistrationValidator;

class UserController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly SessionInterface $session,
        private readonly AuthInterface $authService,
        private readonly RegistrationValidator $registrationValidator,
        private readonly LoginValidator $loginValidator
    ) {
    }

    public function getRegisterPage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('register.html.twig', ['errors' => $this->session->get('errors'), 'oldData' => $this->session->get('oldData')]));
        return $response;
    }
    public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userData = $request->getParsedBody();

        $this->registrationValidator->validate($userData);

        if ($this->authService->userExists($userData)) {
            $oldData = array_diff_key($userData, ['password' => '', 'confirmPassword' => '']);
            throw new ValidationException(['email' => ['Email taken.']], $oldData);
        }

        $user = $this->authService->register($userData);

        if (isset($user)) {
            $this->session->set('user', $user);
            $this->session->unset('errors');
            $this->session->unset('oldData');
            $this->authService->sendVerificationCode($user);
            return $response
                ->withStatus(StatusCodeInterface::STATUS_FOUND)
                ->withHeader('Location', '/');
        }

        $oldData = array_diff_key($userData, ['password' => '', 'confirmPassword' => '']);
        throw new ValidationException([], $oldData);
    }

    public function getLoginPage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('login.html.twig', ['errors' => $this->session->get('errors'), 'oldData' => $this->session->get('oldData')]));
        return $response;
    }
    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userData = $request->getParsedBody();

        $this->loginValidator->validate($userData);

        $user = $this->authService->login($userData);

        if (isset($user)) {

            $this->session->set('user', $user);
            $this->session->unset('errors');
            $this->session->unset('oldData');

            $this->twig->addGlobal('verified', $user->getVerified());

            return $response
                ->withStatus(StatusCodeInterface::STATUS_FOUND)
                ->withHeader('Location', '/');
        }
        $oldData = ['email' => $userData['email']];
        throw new ValidationException(['email' => ['Invalid credentials.']], $oldData);
    }
    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->session->reset();
        return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', '/login');
    }
    public function verify(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->authService->tryVerify($request->getParsedBody()['code'])) {
            $this->session->set('verified', true);
            return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', '/');
        }
        return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
    }
}