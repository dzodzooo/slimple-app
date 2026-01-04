<?php
declare(strict_types=1);
namespace App\Controller;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function __construct(private readonly \Twig\Environment $twig)
    {
    }
    public function home(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!isset($_SESSION) or !isset($_SESSION['user'])) {
            return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', '/login');
        }
        $this->twig->addGlobal('username', $_SESSION['user']->getName());
        $response->getBody()->write($this->twig->render('home.html.twig', []));
        return $response;
    }
}