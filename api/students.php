<?php
require_once '../config/auth.php';
require_once '../config/db.php';
checkApiAuth();
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') { $stmt=$pdo->query("SELECT students.*, groups_list.name AS group_name FROM students JOIN groups_list ON students.group_id=groups_list.id ORDER BY students.id DESC"); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'POST') { $d=json_decode(file_get_contents('php://input'),true); $gid=(int)($d['group_id']??0); $name=trim($d['full_name']??''); if($gid<=0||$name===''){http_response_code(400); echo json_encode(['message'=>'Выберите группу и заполните ФИО'],JSON_UNESCAPED_UNICODE); exit;} $ch=$pdo->prepare("SELECT id FROM groups_list WHERE id=?"); $ch->execute([$gid]); if(!$ch->fetch()){http_response_code(400); echo json_encode(['message'=>'Выбранная группа не существует'],JSON_UNESCAPED_UNICODE); exit;} $stmt=$pdo->prepare("INSERT INTO students (group_id,full_name,birth_date,email,phone,address,admission_year) VALUES (?,?,?,?,?,?,?)"); $stmt->execute([$gid,$name,($d['birth_date']??'')?:null,$d['email']??'',$d['phone']??'',$d['address']??'',($d['admission_year']??'')?:null]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Добавлен студент']); echo json_encode(['message'=>'Студент добавлен'],JSON_UNESCAPED_UNICODE); exit; }
if ($method === 'DELETE') { $pdo->prepare("DELETE FROM students WHERE id=?")->execute([$_GET['id']??0]); $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([currentUserId(),'Удален студент']); echo json_encode(['message'=>'Студент удален'],JSON_UNESCAPED_UNICODE); exit; }
?>
