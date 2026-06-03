<?php
session_start();
require_once __DIR__ . '/db.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $max_participants = (int)($_POST['max_participants'] ?? 0);
    $organizer = trim($_POST['organizer'] ?? '');

    // Валидация
    if (empty($title) || empty($category) || empty($location) || empty($event_date) || $max_participants <= 0) {
        $error = 'Заполните все обязательные поля';
    } else {
        // Обработка изображения
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/upload/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('event_') . '.' . $extension;
            $uploadPath = $uploadDir . $imageName;
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
            }
        }

        // Сохраняем мероприятие
        $stmt = $pdo->prepare("INSERT INTO events (title, description, image_url, category, location, event_date, max_participants, current_participants, organizer, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?)");
        $stmt->execute([
            $title,
            $description,
            $imageName ? '/upload/events/' . $imageName : null,
            $category,
            $location,
            $event_date,
            $max_participants,
            $organizer ?: $_SESSION['user_name'],
            $_SESSION['user_id']
        ]);

        $success = 'Мероприятие успешно создано!';
    }
}

// Получаем мероприятия, созданные пользователем
$stmt = $pdo->prepare("SELECT * FROM events WHERE created_by = ? ORDER BY event_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$myCreatedEvents = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать мероприятие — ЭкоГород</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #f0fdf4; line-height: 1.5; }
        a { text-decoration: none; color: inherit; }

        :root {
            --forest-50: #f0fdf4; --forest-100: #dcfce7; --forest-200: #bbf7d0;
            --forest-500: #10b981; --forest-600: #059669; --forest-700: #047857;
            --forest-800: #065f46; --forest-900: #064e3b;
        }

        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 50; background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: 4rem; display: flex; align-items: center; }
        @media (min-width: 768px) { .navbar { height: 5rem; } }
        .nav-inner { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 1rem; display: flex; align-items: center; justify-content: space-between; }
        .header-logo { display: flex; align-items: center; gap: 0.5rem; }
        .header-logo-icon { width: 2rem; height: 2rem; border-radius: 0.5rem; background: var(--forest-600); display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .header-logo-icon img { width: 100%; height: 100%; object-fit: contain; }
        .logo-text { font-family: 'Playfair Display', serif; font-size: 1.125rem; font-weight: 700; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; border-radius: 9999px; padding: 0.5rem 1.25rem; transition: 0.2s; cursor: pointer; border: none; font-size: 0.875rem; }
        .btn-ghost { background: transparent; color: #4b5563; }
        .btn-ghost:hover { background: #f3f4f6; }
        .btn-primary { background: var(--forest-600); color: #fff; }
        .btn-primary:hover { background: var(--forest-700); }

        .container { max-width: 800px; margin: 0 auto; padding: 0 1rem; }
        .main-content { padding: 6rem 0 3rem; }
        @media (min-width: 768px) { .main-content { padding-top: 7rem; } }

        .page-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; margin-bottom: 2rem; }

        .form-card { background: #fff; border-radius: 1rem; padding: 2rem; border: 1px solid #f3f4f6; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; outline: none; transition: 0.2s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #34d399; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .alert { padding: 0.75rem 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.875rem; }
        .alert-error { background: #fef2f2; color: #dc2626; }
        .alert-success { background: #f0fdf4; color: #15803d; }

        .event-list { display: flex; flex-direction: column; gap: 1rem; }
        .event-card { background: #fff; border-radius: 0.75rem; padding: 1.25rem; border: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
        .event-card h4 { font-weight: 600; font-size: 0.9375rem; }
        .event-card p { font-size: 0.8125rem; color: #6b7280; }

        .footer { background: var(--forest-900); color: #fff; padding: 3rem 0; margin-top: 3rem; text-align: center; font-size: 0.875rem; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <a href="/" class="header-logo">
            <div class="header-logo-icon"><img src="/img/logo.png" alt=""></div>
            <span class="logo-text">ЭкоГород</span>
        </a>
        <div>
            <a href="/profile.php" class="btn btn-ghost">← В профиль</a>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container">
        <h1 class="page-title">Создать мероприятие</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Название мероприятия *</label>
                    <input type="text" name="title" class="form-input" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Категория *</label>
                        <select name="category" class="form-select" required>
                            <option value="">Выберите категорию</option>
                            <option value="Субботник" <?= ($_POST['category'] ?? '') == 'Субботник' ? 'selected' : '' ?>>Субботник</option>
                            <option value="Лекция и семинар" <?= ($_POST['category'] ?? '') == 'Лекция и семинар' ? 'selected' : '' ?>>Лекция и семинар</option>
                            <option value="Сбор вторсырья" <?= ($_POST['category'] ?? '') == 'Сбор вторсырья' ? 'selected' : '' ?>>Сбор вторсырья</option>
                            <option value="Посадка деревьев" <?= ($_POST['category'] ?? '') == 'Посадка деревьев' ? 'selected' : '' ?>>Посадка деревьев</option>
                            <option value="Экофестиваль" <?= ($_POST['category'] ?? '') == 'Экофестиваль' ? 'selected' : '' ?>>Экофестиваль</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Дата и время *</label>
                        <input type="datetime-local" name="event_date" class="form-input" value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Место проведения *</label>
                    <input type="text" name="location" class="form-input" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Максимум участников *</label>
                        <input type="number" name="max_participants" class="form-input" value="<?= htmlspecialchars($_POST['max_participants'] ?? '50') ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Организатор</label>
                        <input type="text" name="organizer" class="form-input" value="<?= htmlspecialchars($_POST['organizer'] ?? $_SESSION['user_name']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Описание</label>
                    <textarea name="description" class="form-textarea" placeholder="Опишите мероприятие..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Изображение</label>
                    <input type="file" name="image" class="form-input" accept="image/*" style="padding:0.5rem;">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.875rem;">Создать мероприятие</button>
            </form>
        </div>

        <!-- Созданные мероприятия -->
        <?php if (!empty($myCreatedEvents)): ?>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Мои созданные мероприятия</h2>
            <div class="event-list">
                <?php foreach ($myCreatedEvents as $event): ?>
                    <div class="event-card">
                        <div>
                            <h4><?= htmlspecialchars($event['title']) ?></h4>
                            <p><?= date('d.m.Y H:i', strtotime($event['event_date'])) ?> · <?= htmlspecialchars($event['location']) ?></p>
                        </div>
                        <a href="/events/1.php?id=<?= $event['id'] ?>" style="color:var(--forest-600);font-size:0.875rem;">Подробнее →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <p>© 2026 ЭкоГород. Все права защищены.</p>
</footer>

</body>
</html>