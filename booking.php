<?php
require_once 'config/db.php';
//Проверка авторизован ли пользователь
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

//Получение значений из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_type = $_POST['room_type'];           //Тип помещения
    $booking_date = $_POST['booking_date'];     //Выбранная дата конференции
    $payment_method = $_POST['payment_method']; //Метод оплаты
    $user_id = $_SESSION['user_id'];            //Айди создателя формы

    //Защита от SQL-инъекций и ввод данных в базу данных
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_type, booking_date, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $room_type, $booking_date, $payment_method]);
    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Оформление заявки — Конференции.РФ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-container">
    <header>
        <h3>Новая заявка</h3>
    </header>
    <main>
        <h2>Забронировать зал</h2>
        <form method="POST">
            <div class="form-group">
                <label>Выберите помещение</label>
                <select name="room_type" class="form-control" required>
                    <option value="Аудитория">Аудитория</option>
                    <option value="Коворкинг">Коворкинг</option>
                    <option value="Кинозал">Кинозал</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Удобная дата</label>
                <input type="date" name="booking_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Способ оплаты</label>
                <select name="payment_method" class="form-control" required>
                    <option value="предоплата по QR-коду">Предоплата по QR-коду</option>
                    <option value="оплата картой МИР">Оплата картой МИР</option>
                    <option value="постоплата в офисе организации">Постоплата в офисе организации</option>
                </select>
            </div>

            <button type="submit" class="btn">Отправить на согласование</button>
            <a href="profile.php" class="btn btn-secondary">Назад</a>
        </form>
    </main>
</div>
</body>
</html>