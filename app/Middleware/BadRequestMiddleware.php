<?php
declare(strict_types=1);
namespace App\Middleware;

use App\Contracts\SessionInterface;
use Doctrine\ORM\Exception\MissingIdentifierField;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;

class BadRequestMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (MissingIdentifierField $exception) {
            return $this->responseFactory->createResponse(StatusCodeInterface::STATUS_BAD_REQUEST, $exception->getMessage());
        }
    }
}