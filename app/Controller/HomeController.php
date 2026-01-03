<?php
declare(strict_types=1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function __construct(private readonly \Twig\Environment $twig)
    {
    }
    public function home(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('home.html.twig', []));
        return $response;
    }
}