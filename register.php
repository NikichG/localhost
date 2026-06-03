<?php
require_once 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Валидация логина и пароля
    if (strlen($login) < 6 || !preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        $error = "Логин не соответствует требованиям.";
    } elseif (strlen($password) < 8) {
        $error = "Пароль слишком короткий.";
    } else {
        // Проверка уникальности логина
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $error = "Логин уже занят!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (login, password, fullname, phone, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$login, $hashed_password, $fullname, $phone, $email]);
            header("Location: login.php?success=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация — Конференции.РФ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-container">
    <header>
        <h3>Конференции.РФ</h3>
    </header>
    <main>
        <h2>Регистрация</h2>
        <?php if($error): ?><div class="error-hint" style="font-size:14px; margin-bottom:10px;"><?= $error ?></div><?php endif; ?>
        
        <form id="regForm" method="POST" action="">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" id="login" class="form-control" required>
                <div id="login-error" class="error-hint"></div>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <div id="pass-error" class="error-hint"></div>
            </div>
            <div class="form-group">
                <label>ФИО</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Телефон</label>
                <input type="text" name="phone" class="form-control" placeholder="+7 (999) 000-00-00" required>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        <a href="login.php" class="btn btn-secondary" style="font-size:14px;">Уже зарегистрированы? Войти</a>
    </main>
</div>
<script src="js/script.js"></script>
</body>
</html>