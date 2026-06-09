<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') { $stmt=$pdo->query("SELECT * FROM teachers ORDER BY id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'POST') { $d=json_decode(file_get_contents('php://input'),true); if(empty($d['full_name'])){http_response_code(400); echo json_encode(['message'=>'Введите ФИО преподавателя'],JSON_UNESCAPED_UNICODE); exit;} $stmt=$pdo->prepare("INSERT INTO teachers (full_name,email,phone,department) VALUES (?,?,?,?)"); $stmt->execute([$d['full_name'],$d['email']??'',$d['phone']??'',$d['department']??'']); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Добавлен преподаватель']); echo json_encode(['message'=>'Преподаватель добавлен'],JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'DELETE') { $pdo->prepare("DELETE FROM teachers WHERE id=?")->execute([$_GET['id']??0]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Удален преподаватель']); echo json_encode(['message'=>'Преподаватель удален'],JSON_UNESCAPED_UNICODE); exit; }
?>
