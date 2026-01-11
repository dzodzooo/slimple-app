<?php
declare(strict_types=1);
namespace App\Controller;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly SessionInterface $session
    ) {
    }
    public function home(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->twig->addGlobal('username', $this->session->get('user')->name);
        $response->getBody()->write($this->twig->render('home.html.twig', []));
        return $response;
    }
}