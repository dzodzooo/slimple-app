<?php
declare(strict_types=1);
namespace App\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseInterface;
use Slim\Csrf\Guard;

class CsrfMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly Guard $csrf
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $csrf = [
            'NameKey' => $nameKey,
            'ValueKey' => $valueKey,
            'Name' => $name,
            'Value' => $value
        ];
        $this->twig->addGlobal('csrf', $csrf);
        return $handler->handle($request);
    }
}