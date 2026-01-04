<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

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
    __DIR__ . '/xml'
]);
$ORMconfig->setProxyDir(__DIR__ . '/Proxies');
$ORMconfig->setProxyNamespace('Proxies');
$entityManager = new EntityManager($connection, $ORMconfig);

$config = new PhpFile(__DIR__ . '/migrations.php');
return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));