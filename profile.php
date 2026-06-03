<?php
require_once 'config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

// Обработка отправки отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $feedback = trim($_POST['feedback']);
    
    // Проверяем, действительно ли мероприятие сменило статус с "Новая"
    $stmt = $pdo->prepare("UPDATE bookings SET feedback = ? WHERE id = ? AND user_id = ? AND status != 'Новая'");
    $stmt->execute([$feedback, $booking_id, $user_id]);
    header("Location: profile.php");
    exit;
}

// Получаем историю заявок
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет — Конференции.РФ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-container">
    <header>
        <h3>Панель пользователя</h3>
        <p><?= htmlspecialchars($_SESSION['fullname']) ?> (<a href="logout.php" style="color:#fff;">Выйти</a>)</p>
    </header>
    
    <div class="slider-container">
        <button class="slider-btn prev">❮</button>
        <button class="slider-btn next">❯</button>
        <div class="slides">
            <div class="slide"><img src="" alt="Зал 1"></div>
            <div class="slide"><img src="" alt="Зал 2"></div>
            <div class="slide"><img src="" alt="Зал 3"></div>
            <div class="slide"><img src="" alt="Зал 4"></div>
        </div>
    </div>

    <main style="padding-top: 0;">
        <a href="booking.php" class="btn" style="margin-bottom:20px;">+ Забронировать помещение</a>
        
        <h2>История ваших заявок</h2>
        <?php if(empty($bookings)): ?><p>Вы еще не создали ни одной заявки.</p><?php endif; ?>
        
        <?php foreach($bookings as $b): ?>
            <div class="card">
                <h3><?= $b['room_type'] ?></h3>
                <p>Дата: <?= date('d.M.Y', strtotime($b['booking_date'])) ?></p>
                <p>Оплата: <?= $b['payment_method'] ?></p>
                
                <p style="margin: 8px 0;">Статус: 
                    <?php if($b['status'] == 'Новая'): ?>
                        <span class="status-badge status-new">Новая</span>
                    <?php elseif($b['status'] == 'Мероприятие назначено'): ?>
                        <span class="status-badge status-assigned">Назначено</span>
                    <?php else: ?>
                        <span class="status-badge status-completed">Завершено</span>
                    <?php endif; ?>
                </p>

                <?php if($b['status'] !== 'Новая'): ?>
                    <?php if(empty($b['feedback'])): ?>
                        <form method="POST" action="" style="margin-top:10px;">
                            <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                            <input type="text" name="feedback" class="form-control" placeholder="Ваш отзыв..." required style="padding:5px; font-size:14px; margin-bottom:5px;">
                            <button type="submit" class="btn" style="padding:5px; font-size:12px; width:auto;">Оставить отзыв</button>
                        </form>
                    <?php else: ?>
                        <p><i>Отзыв: <?= htmlspecialchars($b['feedback']) ?></i></p>
                    <?php endif; ?>
                <?php else: ?>
                    <small style="color:gray;">Отзыв доступен после обработки заявки администратором.</small>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>г. Москва, ул. Большая Ордынка, д. 15</p>
        <p>Тел: +7 (495) 123-45-67</p>
    </footer>
</div>
<script src="js/script.js"></script>
</body>
</html>