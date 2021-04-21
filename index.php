<?php

declare(strict_types=1);

namespace App;


require_once("src/Utils/debug.php");
require_once("src/Controller.php");

$configuration = require_once("config/config.php");

error_reporting(E_ALL);
ini_set('display_errors','1');

$request = [
    'get' => $_GET,
    'post' => $_POST
];


Controller::initConfiguration($configuration);
(new Controller($request))->run();






