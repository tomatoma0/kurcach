<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') { $stmt=$pdo->query("SELECT grades.*, students.full_name AS student_name, subjects.name AS subject_name FROM grades JOIN students ON grades.student_id=students.id JOIN subjects ON grades.subject_id=subjects.id ORDER BY grades.id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'POST') { $d=json_decode(file_get_contents('php://input'),true); $sid=(int)($d['student_id']??0); $sub=(int)($d['subject_id']??0); $grade=(int)($d['grade_value']??0); $date=$d['grade_date']??''; if($sid<=0||$sub<=0||$grade<1||$grade>5||$date===''){http_response_code(400); echo json_encode(['message'=>'Проверьте студента, предмет, дату и оценку от 1 до 5'],JSON_UNESCAPED_UNICODE); exit;} $st=$pdo->prepare("SELECT id FROM students WHERE id=?"); $st->execute([$sid]); $sb=$pdo->prepare("SELECT id FROM subjects WHERE id=?"); $sb->execute([$sub]); if(!$st->fetch()||!$sb->fetch()){http_response_code(400); echo json_encode(['message'=>'Студент или предмет не существует'],JSON_UNESCAPED_UNICODE); exit;} $stmt=$pdo->prepare("INSERT INTO grades (student_id,subject_id,grade_value,grade_date,comment) VALUES (?,?,?,?,?)"); $stmt->execute([$sid,$sub,$grade,$date,$d['comment']??'']); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Добавлена оценка']); echo json_encode(['message'=>'Оценка добавлена'],JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'DELETE') { $pdo->prepare("DELETE FROM grades WHERE id=?")->execute([$_GET['id']??0]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Удалена оценка']); echo json_encode(['message'=>'Оценка удалена'],JSON_UNESCAPED_UNICODE); exit; }
?>
