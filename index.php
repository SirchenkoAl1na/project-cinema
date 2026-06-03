<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . '/vendor/autoload.php';

use App\UserType;
use App\Router;
use App\Migration;

session_start();
// var_dump($_SESSION['user']);
Migration::run();

$router = new Router();

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($url, $method);
