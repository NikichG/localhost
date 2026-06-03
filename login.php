<?php
require_once 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    //проверка хэша или прямого совпадения для демо-пароля админа
    if ($user && (password_verify($password, $user['password']) || $password === 'Demo20' && $login === 'Admin26')) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['fullname'];
        
        if ($user['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: profile.php");
        }
        exit;
    } else {
        $error = "Неправильный логин или пароль.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Конференции.РФ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-container">
    <header>
        <h3>Конференции.РФ</h3>
    </header>
    <main>
        <h2>Вход в систему</h2>
        <?php if(isset($_GET['success'])): ?><div style="color:green; margin-bottom:10px;">Регистрация успешна!</div><?php endif; ?>
        <?php if($error): ?><div class="error-hint" style="font-size:14px; margin-bottom:10px;"><?= $error ?></div><?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>
        <a href="register.php" class="btn btn-secondary" style="font-size:13px;">Еще не зарегистрированы? Регистрация</a>
    </main>
</div>
</body>
</html>