<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Вход | Студенческий портал</title><link rel="stylesheet" href="public/style.css"></head>
<body class="login-page"><div class="login-card"><h1>Студенческий портал</h1><p>Вход в систему учёта студентов, групп, предметов, оценок и посещаемости</p><form action="login_handler.php" method="POST"><label>Email</label><input type="email" name="email" value="admin@mail.com" required><label>Пароль</label><input type="password" name="password" value="admin123" required><button type="submit">Войти</button></form><p class="hint">Тестовый вход: admin@mail.com / admin123</p><?php if(isset($_GET['error'])): ?><p class="error-message">Неверный email или пароль</p><?php endif; ?></div></body></html>
