<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\AuthController;
use Entities\Database;
use Repository\UserRepository;

$database = new Database();
$userRepository = new UserRepository($database);
$authAction = new AuthController($userRepository);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $authAction->handleRequest();
}

if(isset($_COOKIE['user_id_new_Project']) || isset($_SESSION['user_id_new_Project'])){
    $authAction->redirectToHomePage();
}

require_once 'src/Views/register-page.php';