<?php
declare(strict_types=1);
namespace Config;

use App\Middleware\SessionMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(ValidationExceptionMiddleware::class)
        ->add(SessionMiddleware::class);
};