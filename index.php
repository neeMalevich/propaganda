<?php
require_once 'vendor/autoload.php';

use App\Controller\UsersController;
use App\Database\Database;
use MiladRahimi\PhpRouter\Router;

$database = Database::getInstance();
$pdo = $database->getConnection();

$router = Router::create();

$router->post('/webhook', [UsersController::class, 'index']);

$router->dispatch();