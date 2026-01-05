<?php
declare(strict_types=1);
namespace App\Middleware;

use App\Contracts\SessionInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;

class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly SessionInterface $session
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->hasStarted() and $this->session->get('user')) {
            return $this->responseFactory->createResponse()
                ->withStatus(StatusCodeInterface::STATUS_FOUND)
                ->withHeader('Location', '/');
        }
        return $handler->handle($request);
    }
}