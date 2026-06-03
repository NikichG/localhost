<?php
require_once 'config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: login.php"); exit; }

// Смена статуса заявки администратором
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = $_GET['action'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: admin.php");
    exit;
}

// Фильтрация и сортировка
$filter_room = isset($_GET['filter_room']) ? $_GET['filter_room'] : '';
$sort_order = (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'ASC' : 'DESC';

$query = "SELECT b.*, u.fullname, u.phone FROM bookings b JOIN users u ON b.user_id = u.id";
$params = [];

if ($filter_room) {
    $query .= " WHERE b.room_type = ?";
    $params[] = $filter_room;
}
$query .= " ORDER BY b.booking_date $sort_order";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$all_bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель Администратора — Конференции.РФ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 420px;"> <header>
        <h3>Панель управления Admin</h3>
        <p><a href="logout.php" style="color:#fff;">Выйти из панели</a></p>
    </header>
    <main>
        <h2>Все поступающие заявки</h2>
        
        <form method="GET" action="" style="margin-bottom: 20px; background:#f1f3f5; padding:10px; border-radius:6px;">
            <div class="form-group">
                <label style="font-size:12px;">Фильтр по залу:</label>
              <select name="filter_room" class="form-control" style="padding:5px; font-size:14px;" onchange="this.form.submit()">
                    <option value="">Все залы</option>
                    <option value="Аудитория" <?= $filter_room=='Аудитория'?'selected':'' ?>>Аудитория</option>
                    <option value="Коворкинг" <?= $filter_room=='Коворкинг'?'selected':'' ?>>Коворкинг</option>
                    <option value="Кинозал" <?= $filter_room=='Кинозал'?'selected':'' ?>>Кинозал</option>
                </select>  
            </div>
            <div style="font-size:12px;">
                Сортировка даты: 
                <a href="admin.php?sort=asc&filter_room=<?= $filter_room ?>">Сначала старые</a> | 
                <a href="admin.php?sort=desc&filter_room=<?= $filter_room ?>">Сначала новые</a>
            </div>
        </form>

        <?php foreach($all_bookings as $b): ?>
            <div class="card" style="font-size:14px;">
                <strong>Заказчик:</strong> <?= htmlspecialchars($b['fullname']) ?> (<?= htmlspecialchars($b['phone']) ?>)<br>
                <strong>Зал:</strong> <?= $b['room_type'] ?><br>
                <strong>Дата:</strong> <?= date('d.m.Y', strtotime($b['booking_date'])) ?><br>
                <strong>Текущий статус:</strong> 
                <span class="status-badge <?= $b['status']=='Новая'?'status-new':($b['status']=='Мероприятие назначено'?'status-assigned':'status-completed') ?>">
                    <?= $b['status'] ?>
                </span>
                
                <div style="margin-top:10px; border-top:1px dashed #ced4da; padding-top:8px;">
                    <span style="font-size:12px; font-weight:bold; display:block; margin-bottom:4px;">Изменить статус на:</span>
                    <a href="admin.php?id=<?= $b['id'] ?>&action=Мероприятие назначено" class="btn" style="padding:4px 8px; font-size:12px; display:inline-block; width:auto; background:#17a2b8;">Назначено</a>
                    <a href="admin.php?id=<?= $b['id'] ?>&action=Мероприятие завершено" class="btn" style="padding:4px 8px; font-size:12px; display:inline-block; width:auto; background:var(--green);">Завершено</a>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
</div>
</body>
</html>