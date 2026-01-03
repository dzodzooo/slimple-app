<?php
declare(strict_types=1);
namespace App;
require_once __DIR__ . '/../vendor/autoload.php';
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
$router = require_once(__DIR__ . '/Router.php');
$addMiddleware = require_once(__DIR__ . '/../config/middleware.php');

$container = (new ContainerBuilder())->addDefinitions(__DIR__ . '/container-definitions.php')->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$router($app);
$addMiddleware($app);

$app->run();
