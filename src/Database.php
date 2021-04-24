<?php 

declare(strict_types=1);

namespace App;

require_once('Exception/StorageException.php');
require_once('Exception/ConfigurationException.php');

use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use Exception;
use PDO;

class Database
{
    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch(PDOException $e) {
            throw new StorageException('Conectionn Error');
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $conection = new PDO(
            $dsn,
            $config['user'],
            $config['password']
        );

        dump($conection);
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['database'])
            || empty($config['host'])
            || empty($config['user'])
            || empty($config['password'])
         ) {
            throw new ConfigurationException('Storage configuration error');
        }
    }
}