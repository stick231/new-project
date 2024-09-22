<?php

namespace Controllers;

class AuthController{
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