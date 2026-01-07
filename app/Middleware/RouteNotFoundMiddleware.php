<?php
declare(strict_types=1);
namespace App\Middleware;

use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \App\Contracts\SessionInterface;
use Fig\Http\Message\StatusCodeInterface;
use RuntimeException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\ResponseFactory;

class RouteNotFoundMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly SessionInterface $session
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException $exception) {
            $location = ($this->session->hasStarted() and $this->session->get('user')) ? '/' : '/login';
            return $this->responseFactory->createResponse()->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', $location);
        }
    }

}