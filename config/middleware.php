<?php
declare(strict_types=1);
namespace Config;

use App\Middleware\CsrfMiddleware;
use App\Middleware\SessionMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Middleware\MethodOverrideMiddleware;

return function (App $app) {
    $app->add(MethodOverrideMiddleware::class)
        ->add(CsrfMiddleware::class)
        ->add(Guard::class)
        ->add(ValidationExceptionMiddleware::class)
        ->add(SessionMiddleware::class);
};