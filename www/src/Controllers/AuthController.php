<?php

namespace Controllers;

use Entities\User;
use Repository\UserRepository;

class AuthController{
    private $userRepository;

    public function __construct(UserRepository $userRepository) {// нашел лучший вариант mvc решения моей реализации кода. Стоит handle который все обрабатывает, но я не созданию объекты классов в нем.
        $this->userRepository = $userRepository;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) { 
                switch ($_POST['action']) {
                    case 'auth':
                        $this->handleAuth();
                        break;
                    case 'register':
                        $this->handleRegister();
                        break;
                }
            }
        }
    }

    private function handleAuth() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $response = $this->authUser((new User)
                ->setUsername($_POST['username'])
                ->setPassword($_POST['password']));
            
            if ($response) {
                echo json_encode(array('success' => true));
                exit;
            } else {
                echo json_encode(array('success' => false, 'message' => $_SESSION['auth_warning']));
                exit;
            }
        }
    }
    
    private function handleRegister() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $response = $this->registerUser((new User)
                ->setUsername($_POST['username'])
                ->setPassword($_POST['password']));
            if($response){
                echo json_encode(array('success' => true));
                exit;
            }
            else{
                $response = "Такой пользователь уже есть!";
                echo json_encode(array('success' => false, 'message' => $response));
                exit; 
            }
        }
    }

    public function registerUser(User $user)
    {
        return $this->userRepository->registerUser($user);
    }

    public function authUser(User $user)
    {
        return $this->userRepository->authUser($user);
    }

    public function redirectToHomePage()
    {
        header('Location: /');
    }

    public function redirectToAuthPage()
    {
        include 'auth.php';
        exit;
    }

    public function redirectToRegisterPage()
    {
        include 'register.php';
        exit;
    }
}