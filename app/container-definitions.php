<?php

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use \App\Session;
use \App\Contracts\AuthInterface;
use \App\Contracts\CategoryRepositoryInterface;
use \App\Contracts\SessionInterface;
use \App\Contracts\TransactionRepositoryInterface;
use \App\Contracts\UserRepositoryInterface;
use \App\Services\AuthService;
use \App\Repository\CategoryRepository;
use \App\Repository\UserRepository;
use \App\Repository\TransactionRepository;
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;

return [
    EntityManager::class => function () {

        $connectionParams = [
            'driver' => isset($_ENV['DB_DRIVER']) ? $_ENV['DB_DRIVER'] : 'pdo_mysql',
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'dbname' => $_ENV['DB_NAME']
        ];
        $connection = DriverManager::getConnection($connectionParams);

        $ORMconfig = ORMSetup::createXMLMetadataConfig([
            __DIR__ . '/../config/xml/'
        ]);
        $ORMconfig->setProxyDir(__DIR__ . '/../config/Proxies');
        $ORMconfig->setProxyNamespace('Config\\Proxies');
        return new EntityManager($connection, $ORMconfig);
    },
    \Twig\Environment::class => function () {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/Templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => $_ENV['ENVIRONMENT'] === 'DEV' ? false : __DIR__ . '/Templates/cache',
            'auto_reload' => $_ENV['ENVIRONMENT'] === 'DEV'
        ]);

        return $twig;
    },
    SessionInterface::class => fn() => new Session(),
    AuthInterface::class => fn(UserRepositoryInterface $userRepository) => new AuthService($userRepository),
    UserRepositoryInterface::class => fn(EntityManager $entityManager) => new UserRepository($entityManager),
    TransactionRepositoryInterface::class => fn(EntityManager $entityManager, SessionInterface $session) => new TransactionRepository($entityManager, $session),
    CategoryRepositoryInterface::class => fn(EntityManager $entityManager, SessionInterface $session) => new CategoryRepository($entityManager, $session),
    Guard::class => fn(ResponseFactory $responseFactory) => new Guard($responseFactory, persistentTokenMode: true)

];