<?php

use App\Repositories\BaseRepository;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Dotenv\Dotenv;

//--------------------
ini_set('display_errors', 1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set("memory_limit", "-1");
//--------------------


//--------------------
require_once "vendor/autoload.php";
//--------------------


//--------------------
// Create a simple "default" Doctrine ORM configuration for Annotations
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../app/Entity"), true, null, null, false);
//--------------------


//--------------------
// database configuration parameters
(new Dotenv(true))->loadEnv(__DIR__ . '/../.env');
//--------------------

//--------------------

$conn = [
    'dbname' => getenv('DB_DATABASE'),
    'user' => getenv('DB_USERNAME'),
    'host' => getenv('DB_HOST'),
    'password' => getenv('DB_PASSWORD'),
    'driver' => getenv('DB_DRIVER'),
];

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
BaseRepository::setEntityManager($entityManager);


//--------------------

try {
    require_once 'routes/api.php';
} catch (\Exception $exception) {

}

