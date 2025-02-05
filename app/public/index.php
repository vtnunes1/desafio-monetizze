<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;

$router = new Router();

require_once __DIR__ . '/../src/Routes/web.php';
require_once __DIR__ . '/../src/Routes/api.php';

//** CHAMA ROUTER PARA TRATAR REQUISICAO **//
$router->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
