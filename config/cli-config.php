<?php
namespace Config;
require_once __DIR__ . '/../vendor/autoload.php';
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

return function (EntityManager $entityManager) {
    $config = new PhpFile(__DIR__ . '/migrations.php');
    return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
};