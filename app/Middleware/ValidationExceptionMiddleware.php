<?php
declare(strict_types=1);
namespace App\Middleware;

use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \App\Contracts\SessionInterface;
use Slim\Psr7\Factory\ResponseFactory;

class ValidationExceptionMiddleware implements MiddlewareInterface
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
        } catch (ValidationException $exception) {

            if (null === $this->session->get('errors')) {
                $this->session->set('errors', $exception->errors);
                $this->session->set('oldData', $exception->oldData);
            } else {
                $this->session->set('errors', null);
                $this->session->set('oldData', null);
            }
            $path = parse_url($request->getHeader('Referer')[0], PHP_URL_PATH);

            return $this->responseFactory->createResponse()->withHeader('Location', $path)->withStatus(302);
        }
    }

}