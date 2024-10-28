<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/style/styles_auth.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <form method="post" action="/register" id="formRegister">
    <h1>Регистрация</h1>
        <div id="error-message"></div>
        <input type="text" id="username" name="username" placeholder="Логин..">
        <br>
        <input type="password" id="password" placeholder="Пароль.." name="password">
        <br>
        <a id="link">Уже зарегистрирован</a>
        <button type="submit" id="submit">Войти</button>
    </form>
    <script src="assets/script/script-register.js"></script>
</body>
</html>