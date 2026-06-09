<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

$stmt=$pdo->query("SELECT logs.*, users.full_name AS user_name, users.email AS user_email FROM logs LEFT JOIN users ON logs.user_id=users.id ORDER BY logs.id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>
