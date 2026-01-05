<?php
declare(strict_types=1);
namespace App\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactory $responseFactory)
    {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (isset($_SESSION) and isset($_SESSION['user'])) {
            return $handler->handle($request);
        }
        return $this->responseFactory->createResponse()
            ->withStatus(StatusCodeInterface::STATUS_FOUND)
            ->withHeader('Location', '/login');
    }
}