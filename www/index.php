<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use Controllers\AuthController;
use Controllers\AuthMiddleWare;
use Entities\Database;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use Repository\UserRepository;

$router = new RouteCollector();

$authMiddleWare = new AuthMiddleWare();

$router->get('/', function() use ($authMiddleWare){
    $authMiddleWare->handle($_REQUEST, function(){

    });
});

$dataBase = new Database();
$userRepository = new UserRepository($dataBase);
$authAction = new AuthController($userRepository);

$router->any('/register', function() use ($authAction){
    $authAction->redirectToRegisterPage();
});

$router->any('/auth', function() use ($authAction){
    $authAction->redirectToAuthPage();
});

$dispatcher = new Dispatcher($router->getData());

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

try {
    echo $dispatcher->dispatch($httpMethod, $uri);
} catch (Phroute\Phroute\Exception\HttpMethodNotAllowedException $e) {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
    echo 'Метод не разрешен: ' . $e->getMessage();
    exit;
} catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo 'Маршрут не найден: ' . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo 'Произошла ошибка: ' . $e->getMessage();
    exit;
}

require_once "src/Views/home-page.html";