<?php
declare(strict_types=1);
namespace Config;

use App\Middleware\CsrfMiddleware;
use App\Middleware\RouteNotFoundMiddleware;
use App\Middleware\SessionMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Middleware\MethodOverrideMiddleware;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return function (App $app) {
    $app->add(MethodOverrideMiddleware::class)
        ->add(CsrfMiddleware::class)
        ->add(Guard::class)
        ->add(ValidationExceptionMiddleware::class)
        ->add(RouteNotFoundMiddleware::class)
        ->add(SessionMiddleware::class);
};