<?php
namespace Controllers;


class AuthMiddleWare{
    public function handle($request, callable $next){
        if(!isset($_COOKIE['user_id_new_Project'])){
            $_SESSION['register_error'] = 'Пожалуйста, пройдите регистрацию.'; 
            header("Location: /register");
            exit; 
        }
        
        if(!isset($_SESSION['user_id_new_Project'])){
            $_SESSION['auth_error'] = 'Пожалуйста, войдите в систему.';
            header("Location: /auth");
            exit; 
        } 

        return $next($request);
    }
}