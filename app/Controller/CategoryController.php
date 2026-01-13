<?php
declare(strict_types=1);
namespace App\Controller;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\SessionInterface;
use App\Entity\Category;
use App\Exception\ValidationException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly SessionInterface $session
    ) {
    }
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categories = $this->categoryRepository->getAll();
        $this->twig->addGlobal('categories', $categories);
        $response->getBody()->write($this->twig->render('category/categories.html.twig', [
            'errors' => $this->session->get('errors'),
            'oldData' => $this->session->get('oldData')
        ]));
        return $response;
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categoryData = $request->getParsedBody();
        if ($this->categoryRepository->getByName($categoryData['name']) instanceof Category) {
            throw new ValidationException(['name' => ['Category already exists.']], $categoryData);
        }
        $this->categoryRepository->create($categoryData);
        $this->session->unset('errors');
        $this->session->unset('oldData');
        return $response->withHeader('Location', '/categories')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categoryData = $request->getParsedBody()['category'];
        if (!$this->categoryRepository->update($categoryData)) {
            return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
        }
        return $response->withStatus(StatusCodeInterface::STATUS_OK)->withHeader('blep', 'blep');
    }
    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $this->categoryRepository->delete($id);
        return $response->withHeader('Location', '/categories')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
}