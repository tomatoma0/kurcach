<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') { $stmt=$pdo->query("SELECT subjects.*, teachers.full_name AS teacher_name FROM subjects LEFT JOIN teachers ON subjects.teacher_id=teachers.id ORDER BY subjects.id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'POST') { $d=json_decode(file_get_contents('php://input'),true); $tid=($d['teacher_id']??'')!==''?(int)$d['teacher_id']:null; $name=trim($d['name']??''); if($name===''){http_response_code(400); echo json_encode(['message'=>'Введите название предмета'],JSON_UNESCAPED_UNICODE); exit;} if($tid!==null){$ch=$pdo->prepare("SELECT id FROM teachers WHERE id=?"); $ch->execute([$tid]); if(!$ch->fetch()){http_response_code(400); echo json_encode(['message'=>'Выбранный преподаватель не существует'],JSON_UNESCAPED_UNICODE); exit;}} $stmt=$pdo->prepare("INSERT INTO subjects (teacher_id,name,hours_count,semester) VALUES (?,?,?,?)"); $stmt->execute([$tid,$name,($d['hours_count']??'')?:0,($d['semester']??'')?:null]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Добавлен предмет']); echo json_encode(['message'=>'Предмет добавлен'],JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'DELETE') { $pdo->prepare("DELETE FROM subjects WHERE id=?")->execute([$_GET['id']??0]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Удален предмет']); echo json_encode(['message'=>'Предмет удален'],JSON_UNESCAPED_UNICODE); exit; }
?>
