<?php

declare(strict_types=1);

namespace App;

require_once("src/Utils/debug.php");
require_once("src/Controller.php");

error_reporting(0);
ini_set('display_errors','0');

$request = [
    'get' => $_GET,
    'post' => $_POST
];

(new Controller($request))->run();






