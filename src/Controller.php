<?php

declare(strict_types=1);

namespace App;

require_once("src/Exception/ConfigurationException.php");
require_once("src/Exception/NotFoundException.php");

use App\Exception\NotFoundException;
use App\Exception\ConfigurationException;
use PDO;

require_once("Database.php");
require_once("View.php");


class Controller
{ 
    private const DEFAULT_ACTION = 'list';

    private static array $configuration = [];

    private Database $database;
    private array $request;
    private View $view;

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(array $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException('Configuration error');
        }
        $this->database = new Database(self::$configuration['db']);

        $this->request = $request;
        $this->view = new View();
    }

    public function run(): void
    {
        switch($this->action()) {
            case 'create':
                $page = 'create';
                $data = $this->getRequestPost();
                if (!empty($data)) {
                    $this->database->createNote([
                        'title' => $data['title'],
                        'description' => $data['description']
                        ]
                    );
                    header('Location: /?before=created');
                }
                break;
            case 'show':
                $page = "show";    
                $data = $this->getRequestGet();
                $noteId = (int) $data['id'];

                try {
                    $note = $this->database->getNote($noteId);
                } catch (NotFoundException $e) {
                    header('Location: /?error=noteNotFound');
                }
                
                $viewParams = [
                    'note' => $note,
                ];
                break;
            default:
                $page = 'list';
                $data = $this->getRequestGet();
                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $data['before'] ?? null,
                    'error' => $data['error'] ?? null,
                ];
                break;
        }
        $this->view->render($page, $viewParams ?? []);
    }

    private function getRequestGet(): array
    {
        return $this->request['get'] ?? [];
    }

    private function getRequestPost(): array
    {
        return $this->request['post'] ?? [];
    }

    private function action(): string
    {
        $data = $this->getRequestGet();
        return $data['action'] ?? self::DEFAULT_ACTION;
    }

}