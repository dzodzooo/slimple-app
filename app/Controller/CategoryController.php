<?php
declare(strict_types=1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    public function __construct(private readonly \Twig\Environment $twig)
    {
    }
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('categories.html.twig', []));
        return $response;
    }
}