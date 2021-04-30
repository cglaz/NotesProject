<?php

declare(strict_types=1);

spl_autoload_register(function(string $name) {
    var_dump($name);
});

use App\Request;

$request = new Request($_GET, $_POST);

exit('end');

require_once('src/Exception/AppException.php');
require_once("src/Utils/debug.php");
require_once("src/Controller/NoteController.php");
require_once("src/Request.php");

//use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;

$configuration = require_once("config/config.php");




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




