<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\AuthController;
use Entities\Database;
use Entities\User;
use Repository\UserRepository;


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $database = new Database();

        $userRepository = new UserRepository($database);
        $response = $userRepository->registerUser((new User)
            ->setUsername($_POST['username'])
            ->setPassword($_POST['password']));
        if($response){
            $authAction = new AuthController;
            $authAction->redirectToHomePage();
        }
        else{
            $response = "Такой пользователь уже есть!";
            $_SESSION['register_error'] = $response;
            header("Location: /register"); 
            exit; 
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_auth.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <form method="post" action="/register">
    <h1>Регистрация</h1>
        <?php if (isset($response)){ echo "<p>$response</p>";}?>
        <input type="text" id="username" name="username" placeholder="Логин..">
        <br>
        <input type="password" id="password" placeholder="Пароль.." name="password">
        <br>
        <a id="link">Уже зарегистрирован</a>
        <button type="submit" id="submit">Войти</button>
    </form>
    <script>
        document.getElementById("link").addEventListener("click", () => {
            fetch("/auth?register=true", {
                method: "GET",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            })
            .then(response => {
                if (response.ok) {
                    console.log("Перенаправление на вход");
                    setTimeout(() => {
                        window.location = "/auth";
                    }, 500);
                } else {
                    console.error("Ошибка при перенаправлении:", response.status);
                }
            })
            .catch(error => {
                console.error("Ошибка при выполнении запроса:", error);
            });
        });


    function CheckInp(){
    const inpUsername = document.getElementById("username");
    const inpPassword = document.getElementById("password");

    if(inpUsername.value == null || inpUsername.value == ""){
        alert("Введите имя пользователя");
        return false;
    }

    if(inpPassword.value == null || inpPassword.value == ""){
        alert("Введите пароль");
        return false;
    }

    if(inpUsername.value.length < 4){
        alert("Имя пользователя должно быть больше 4 символов");
        return false;
    }

    if(inpPassword.value.length < 4){
        alert("Пароль должен содержать больше 4 символов");
        return false;
    }
    return true;
}

    document.getElementById("submit").addEventListener("click", (event) =>{
        if (!CheckInp()) {
            event.preventDefault();
        }
    });
    </script>
</body>
</html>