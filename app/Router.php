<?php
declare(strict_types=1);
namespace App;
require_once __DIR__ . '/../vendor/autoload.php';
use Slim\Routing\RouteCollectorProxy;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\TransactionController;
use App\Controller\UserController;

return function ($app) {
    $app->get('/', [HomeController::class, 'home']);

    $app->group('/login', function (RouteCollectorProxy $group) {
        $group->get('', [UserController::class, 'getLoginPage']);
        $group->post('', [UserController::class, 'login']);
    });

    $app->group('/register', function (RouteCollectorProxy $group) {
        $group->get('', [UserController::class, 'getRegisterPage']);
        $group->post('', [UserController::class, 'register']);
    });

    $app->group('/transactions', function (RouteCollectorProxy $group) {
        $group->get('', [TransactionController::class, 'get']);
        $group->post('', [TransactionController::class, 'post']);
    });


    $app->group('/categories', function (RouteCollectorProxy $group) {
        $group->get('', [CategoryController::class, 'get']);
        $group->post('', [CategoryController::class, 'post']);
    });
};