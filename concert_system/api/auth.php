<?php
// api/auth.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method == 'POST') {
    if (isset($data['action'])) {
        switch($data['action']) {
            case 'login':
                loginUser($db, $data);
                break;
            case 'register':
                registerUser($db, $data);
                break;
            case 'logout':
                logoutUser();
                break;
            default:
                echo json_encode(["success" => false, "message" => "Неизвестное действие"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Действие не указано"]);
    }
}

function loginUser($db, $data) {
    if (!isset($data['email']) || !isset($data['password'])) {
        echo json_encode(["success" => false, "message" => "Email и пароль обязательны"]);
        return;
    }
    
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $password = $data['password'];
    
    try {
        $query = "SELECT user_id, email, password_hash, first_name, last_name 
                  FROM users 
                  WHERE email = :email 
                  AND is_active = 1";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                
                echo json_encode([
                    "success" => true,
                    "message" => "Вход выполнен успешно",
                    "user" => [
                        "user_id" => $user['user_id'],
                        "email" => $user['email'],
                        "name" => $user['first_name'] . ' ' . $user['last_name']
                    ]
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Неверный пароль"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Пользователь не найден"]);
        }
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => "Ошибка базы данных"]);
    }
}

function registerUser($db, $data) {
    if (!isset($data['email']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name'])) {
        echo json_encode(["success" => false, "message" => "Все поля обязательны"]);
        return;
    }
    
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $first_name = htmlspecialchars(strip_tags($data['first_name']));
    $last_name = htmlspecialchars(strip_tags($data['last_name']));
    $phone = isset($data['phone']) ? htmlspecialchars(strip_tags($data['phone'])) : null;
    
    try {
        // Проверяем, существует ли email
        $checkQuery = "SELECT user_id FROM users WHERE email = :email";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            echo json_encode(["success" => false, "message" => "Email уже зарегистрирован"]);
            return;
        }
        
        $query = "INSERT INTO users (email, password_hash, first_name, last_name, phone) 
                  VALUES (:email, :password, :first_name, :last_name, :phone)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":phone", $phone);
        
        if ($stmt->execute()) {
            $user_id = $db->lastInsertId();
            
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            
            echo json_encode([
                "success" => true,
                "message" => "Регистрация успешна",
                "user" => [
                    "user_id" => $user_id,
                    "email" => $email,
                    "name" => $first_name . ' ' . $last_name
                ]
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => "Ошибка регистрации"]);
    }
}

function logoutUser() {
    session_destroy();
    echo json_encode(["success" => true, "message" => "Выход выполнен"]);
}
?>