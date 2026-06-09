<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') { $stmt=$pdo->query("SELECT * FROM groups_list ORDER BY id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'POST') { $d=json_decode(file_get_contents('php://input'),true); if(empty($d['name'])||empty($d['course'])){http_response_code(400); echo json_encode(['message'=>'Заполните название группы и курс'],JSON_UNESCAPED_UNICODE); exit;} $stmt=$pdo->prepare("INSERT INTO groups_list (name,course,specialty) VALUES (?,?,?)"); $stmt->execute([$d['name'],$d['course'],$d['specialty']??'']); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Добавлена группа']); echo json_encode(['message'=>'Группа добавлена'],JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'DELETE') { $pdo->prepare("DELETE FROM groups_list WHERE id=?")->execute([$_GET['id']??0]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Удалена группа']); echo json_encode(['message'=>'Группа удалена'],JSON_UNESCAPED_UNICODE); exit; }
?>
