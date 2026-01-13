<?php
namespace Config;
require_once __DIR__ . '/../vendor/autoload.php';
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;

$container_definitions = require_once __DIR__ . "/../app/container-definitions.php";
$entityManager = $container_definitions[EntityManager::class]();
$config = new PhpFile(__DIR__ . '/migrations.php');
return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));