<?php
declare(strict_types=1);
namespace App;
require_once __DIR__ . '/../vendor/autoload.php';
use Slim\Routing\RouteCollectorProxy;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\TransactionController;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\App;

return function (App $app) {
    $app->group('/', function (RouteCollectorProxy $auth) {

        $auth->get('', [HomeController::class, 'home']);

        $auth->get('logout', [UserController::class, 'logout']);

        $auth->group('transactions', function (RouteCollectorProxy $group) {
            $group->get('', [TransactionController::class, 'get']);
            $group->post('', [TransactionController::class, 'post']);
            $group->put('', [TransactionController::class, 'update']);
            $group->delete('/{id}', [TransactionController::class, 'delete']);
        });

        $auth->group(
            'categories',
            function (RouteCollectorProxy $group) {
                $group->get('', [CategoryController::class, 'get']);
                $group->post('', [CategoryController::class, 'post']);
                $group->put('', [CategoryController::class, 'update']);
                $group->delete('/{id}', [CategoryController::class, 'delete']);
            }
        );
    })->add(AuthMiddleware::class);


    $app->group('/login', function (RouteCollectorProxy $group) {
        $group->get('', [UserController::class, 'getLoginPage']);
        $group->post('', [UserController::class, 'login']);
    })->add(GuestMiddleware::class);

    $app->group('/register', function (RouteCollectorProxy $group) {
        $group->get('', [UserController::class, 'getRegisterPage']);
        $group->post('', [UserController::class, 'register']);
    })->add(GuestMiddleware::class);

};