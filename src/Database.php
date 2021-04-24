<?php 

declare(strict_types=1);

namespace App;

require_once('Exception/StorageException.php');
require_once('Exception/ConfigurationException.php');

use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use Exception;
use PDO;
use Throwable;

class Database
{
    private PDO $conn;

    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch(PDOException $e) {
            throw new StorageException('Conection Error');
        }
    }

    public function createNote(array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "INSERT INTO notes(title, description, created)
             VALUES($title, $description, $created)";

            $this->conn->exec($query);
        } catch(Throwable $e) {
            throw new StorageException('Nie udało się stworzyć nowej notatki', 400, $e);
            exit;
        }  
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO(
            $dsn,
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
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