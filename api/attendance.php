<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') { $stmt=$pdo->query("SELECT attendance.*, students.full_name AS student_name, subjects.name AS subject_name FROM attendance JOIN students ON attendance.student_id=students.id JOIN subjects ON attendance.subject_id=subjects.id ORDER BY attendance.id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'POST') { $d=json_decode(file_get_contents('php://input'),true); $sid=(int)($d['student_id']??0); $sub=(int)($d['subject_id']??0); $date=$d['lesson_date']??''; $status=$d['status']??''; if($sid<=0||$sub<=0||$date===''||!in_array($status,['present','absent','late'])){http_response_code(400); echo json_encode(['message'=>'Проверьте студента, предмет, дату и статус'],JSON_UNESCAPED_UNICODE); exit;} $stmt=$pdo->prepare("INSERT INTO attendance (student_id,subject_id,lesson_date,status,comment) VALUES (?,?,?,?,?)"); $stmt->execute([$sid,$sub,$date,$status,$d['comment']??'']); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Добавлена посещаемость']); echo json_encode(['message'=>'Посещаемость добавлена'],JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'DELETE') { $pdo->prepare("DELETE FROM attendance WHERE id=?")->execute([$_GET['id']??0]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Удалена посещаемость']); echo json_encode(['message'=>'Запись посещаемости удалена'],JSON_UNESCAPED_UNICODE); exit; }
?>
