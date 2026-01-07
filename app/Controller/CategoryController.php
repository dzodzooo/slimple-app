<?php
declare(strict_types=1);
namespace App\Controller;

use App\Contracts\CategoryRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categories = $this->categoryRepository->getAll();
        $this->twig->addGlobal('categories', $categories);
        $response->getBody()->write($this->twig->render('categories.html.twig', []));
        return $response;
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categoryData = $request->getParsedBody();
        $this->categoryRepository->create($categoryData);
        return $response->withHeader('Location', '/categories')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categoryData = $request->getParsedBody()['category'];
        $this->categoryRepository->update($categoryData);
        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }
    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $this->categoryRepository->delete($id);
        return $response->withHeader('Location', '/categories')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
}