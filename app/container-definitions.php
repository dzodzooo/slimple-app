<?php

use App\Services\TransactionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use \App\Session;
use \App\Services\AuthService;
use \App\Contracts\AuthInterface;
use \App\Contracts\SessionInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Services\UserRepository;

return [
    EntityManagerInterface::class => function () { },
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
        $ORMconfig->setProxyDir(__DIR__ . '../config/Proxies');
        $ORMconfig->setProxyNamespace('Proxies');
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
    SessionInterface::class => function () {
        return new Session();
    },
    AuthInterface::class => function (UserRepositoryInterface $userRepository) {
        return new AuthService($userRepository);
    },
    UserRepositoryInterface::class => function (EntityManager $entityManager) {
        return new UserRepository($entityManager);
    },
    TransactionRepositoryInterface::class => function (EntityManager $entityManager) {
        return new TransactionRepository($entityManager); }
];