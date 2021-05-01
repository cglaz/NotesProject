<?php

declare(strict_types=1);

spl_autoload_register(function (string $classNamespace) {
    $path = str_replace(['\\', 'App/'],['/', ''],$classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

require_once("src/Utils/debug.php");
$configuration = require_once("config/config.php");

use App\Controller\AbstractController;
use App\Controller\NoteController;
use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;

$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfiguration($configuration);
    (new NoteController($request))->run();
} catch (ConfigurationException $e){
    //mail('xxx@xxxx.com', 'Error', $e->getMessage());
    echo '<h1>Wystąpił błąd aplikacji</h1>';
    echo '<h3>Problem z aplikacją, proszę spróbować za chwilę.</h3>';
} catch (AppException $e) {
    echo "<h1>Wystąpił błąd aplikacji</h1>";
    echo '<h3>'.$e->getMessage().'</h3>';
} catch (\Throwable $e) {
    echo "<h1>Wystąpił błąd aplikacji</h1>";
    dump($e);
}




