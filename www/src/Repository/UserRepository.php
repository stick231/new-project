<?php
namespace Repository;

use Entities\Database;
use Entities\User;

class UserRepository implements UserRepositoryInterface {
    private $pdo;

    public function __construct(Database $dataBase) {
        $this->pdo = $dataBase->getConnection();
    }

    public function checkUserExists(User $user) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$user->getUsername()]); 
            $existingUser = $stmt->fetch();

            return $existingUser ? false : true; 
        } catch (\PDOException $e) {
            echo "Ошибка при проверке пользователя: " . $e->getMessage();
            return false; 
        }
    }

    public function registerUser(User $user) {
        try {
            if ($this->checkUserExists($user)) {
                $stmt = $this->pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
                $stmt->execute([$user->getUsername(), password_hash($user->getPassword(), PASSWORD_DEFAULT)]); // Хешируем пароль

                $userId = $this->pdo->lastInsertId();
                setcookie("user_id_new_Project", $userId, time() + 3600 * 24 * 30, "/"); 
                $_SESSION['user_id_new_Project'] = $userId;
                setcookie("register_new_Project", 'true', time() + 3600 * 24 * 30, "/");
                $_SESSION['just_register'] = $user->getUsername();

                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            echo "Ошибка при регистрации пользователя: " . $e->getMessage();
            return false; 
        }
    }

    public function authUser(User $user) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$user->getUsername()]);
            
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if (!$row) {
                $_SESSION['auth_warning'] = 'Пользователь не найден';
                return false;
            }

            if (password_verify($user->getPassword(), $row['password'])) {
                $userId = $row['id'];
                setcookie("user_id_new_Project", $userId, time() + 3600 * 24 * 30, "/");
                $_SESSION['user_id_new_Project'] = $userId;
                $_SESSION["login"] = $user->getUsername();
                setcookie("register_new_Project", 'true', time() + 3600 * 24 * 30, "/");
                return true;
            } else {
                $_SESSION['auth_warning'] = 'Неверный логин или пароль';
                return false;
            }
        } catch (\PDOException $e) {
            echo 'Ошибка аутентификации: ' . $e->getMessage();
            return false; 
        }
    }
}