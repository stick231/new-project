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
        $response = $userRepository->authUser((new User)
            ->setUsername($_POST['username'])
            ->setPassword($_POST['password']));
        if($response){
            $authAction = new AuthController();
            $authAction->redirectToHomePage();
        }
        else{
            header("Location: /auth");
            exit; 
        }
    }
}

if (isset($_SESSION['auth_warning'])) {
    $response = $_SESSION['auth_warning'];
    unset($_SESSION['auth_warning']); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">   
    <link rel="stylesheet" href="styles_auth.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
    <form method="post" action="/auth">
    <h1>Вход в аккаунт</h1>
        <?php if (isset($response)){echo "<p> $response</p>";}?>
        <input type="text" id="username" name="username" placeholder="Логин..">
        <br>
        <input type="password" id="password" placeholder="Пароль.." name="password">
        <br>
        <a id="link">Еще не регистрировался</a>
        <button type="submit" id="submit">Войти</button>
    </form>
    <script>
        document.getElementById("link").addEventListener("click", () => {
            fetch("/register?register=false", {
                method: "GET",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            })
            .then(response => {
                if (response.ok) {
                    console.log("Перенаправление на регистрацию");
                    setTimeout(() => {
                        window.location = "/register";
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
                alert("Введите имя пользователя")
                return false;
            }

            if(inpPassword.value == null || inpPassword.value == ""){
                alert("Введите пароль")
                return false;
            }

            return true;
        }

        document.getElementById("submit").addEventListener("click", () =>{
            if (!CheckInp()) {
                event.preventDefault();
            }
        })
  </script>
</body>
</html>