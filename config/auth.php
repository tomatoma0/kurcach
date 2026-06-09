<?php
session_start();
function checkAuth() {
    if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
}
function checkApiAuth() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['message'=>'Пользователь не авторизован'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
function currentUserId() { return $_SESSION['user_id'] ?? null; }
?>
<?php
$host = 'localhost';
$dbname = 'student_management';  // Проверьте имя БД
$username = 'root';
$password = '';  // В XAMPP пароль пустой

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
