<?php 

declare(strict_types=1);

namespace App\Model;

use App\Exception\StorageException;
use App\Exception\NotFoundException;
use Throwable;
use PDO;

class NoteModel extends AbstractModel
{
    public function getNotes(
        int $pageNumber, 
        int $pageSize, 
        string $sortBy, 
        string $sortOrder
        ): array
    {
        return $this->findBy(NULL, NULL, $pageNumber, $pageSize, $sortBy, $sortOrder);
    }

    public function searchNotes(
        string $date,
        string $phrase,
        int $pageNumber, 
        int $pageSize, 
        string $sortBy, 
        string $sortOrder
    ): array {
        return $this->findBy($date, $phrase, $pageNumber, $pageSize, $sortBy, $sortOrder);
    }

    public function getCount(): int
    {
        try {
            $query = "SELECT count(*) AS cn FROM notes";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $result = $result->fetch();
            if ($result === false) {
               throw new StorageException('Bład przy próbie pobrania ilości notatek', 400);
            }
            return (int) $result['cn'];
            
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać informacji o liczbie notatek', 400, $e);
        }
    }

    public function getSearchCount(string $date, string $phrase): int
    {
        try {
            $date = $this->conn->quote('%'.$date.'%', PDO::PARAM_STR);
            $phrase= $this->conn->quote('%'.$phrase.'%', PDO::PARAM_STR);
            $query = "SELECT count(*) AS cn FROM notes WHERE title LIKE($phrase) AND created LIKE ($date)";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $result = $result->fetch();
            if ($result === false) {
               throw new StorageException('Bład przy próbie pobrania ilości notatek', 400);
            }
            return (int) $result['cn'];
            
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać informacji o liczbie notatek', 400, $e);
        }
    }

    public function getNote(int $id): array{
        try {
            $query = "SELECT * FROM notes WHERE id = $id";
            $result = $this->conn->query($query);
            $note = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            dump($e);
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }

        if(!$note) {
            throw new NotFoundException("Notatka o id: $id - nie istnieje");
            exit('Nie ma takiej notatki');
        }

        return $note;
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

    public function editNote(int $id, array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "
            UPDATE notes
            SET title = $title, description = $description
            WHERE id = $id;
            ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się zaktualizować notatki', 400, $e);
        }
    }

    public function deleteNote(int $id): void
    {
        try {
            $query = "
            DELETE FROM notes
            WHERE id = $id LIMIT 1;
            ";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się usunąć notatki', 400, $e);
        }
    }
    
    private function findBy(
        ?string $date,
        ?string $phrase,
        int $pageNumber,
        int $pageSize,
        string $sortBy,
        string $sortOrder
      ): array {
        try {
          $limit = $pageSize;
          $offset = ($pageNumber - 1) * $pageSize;
    
          if (!in_array($sortBy, ['created', 'title'])) {
            $sortBy = 'title';
          }
    
          if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
          }
    
          $wherePart = '';

        if (isset($phrase) && isset($date)) {
            $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
            $date = $this->conn->quote('%' . $date . '%', PDO::PARAM_STR);
            $wherePart = "WHERE title LIKE ($phrase) AND created LIKE ($date)";
        } 
        else if ($phrase) {
            $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
            $wherePart = "WHERE title LIKE ($phrase)";
        } else if ($date) {
             $date = $this->conn->quote('%' . $date . '%', PDO::PARAM_STR);
            $wherePart = "WHERE created LIKE ($date)";
        }

          $query = "
            SELECT id, title, created 
            FROM notes
            $wherePart
            ORDER BY $sortBy $sortOrder
            LIMIT $offset, $limit
          ";
          dump($query);
    
          $result = $this->conn->query($query);
          return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
          throw new StorageException('Nie udało się pobrać notatek', 400, $e);
        }
      }
}