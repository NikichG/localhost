<?php
session_start();
require_once __DIR__ . '/../db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    http_response_code(404);
    die("Мероприятие не найдено");
}

$current = (int)$event['current_participants'];
$max = (int)$event['max_participants'];
$percent = $max > 0 ? round(($current / $max) * 100, 2) : 0;

// Проверяем, зарегистрирован ли пользователь
$isRegistered = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ? AND status != 'cancelled' LIMIT 1");
    $stmt->execute([$_SESSION['user_id'], $id]);
    $isRegistered = (bool)$stmt->fetch();
}

// Обработка регистрации
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'register') {
        // Проверяем авторизацию
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login.php?redirect=/events/1.php?id=' . $id);
            exit;
        }

        // Проверяем, не зарегистрирован ли уже
        if ($isRegistered) {
            $message = 'Вы уже зарегистрированы на это мероприятие';
            $messageType = 'error';
        } elseif ($current >= $max) {
            $message = 'К сожалению, все места уже заняты';
            $messageType = 'error';
        } else {
            // Регистрируем пользователя
            $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $id]);

            // Увеличиваем счётчик участников
            $stmt = $pdo->prepare("UPDATE events SET current_participants = current_participants + 1 WHERE id = ?");
            $stmt->execute([$id]);

            $message = 'Вы успешно зарегистрировались на мероприятие!';
            $messageType = 'success';
            $current++;
            $percent = $max > 0 ? round(($current / $max) * 100, 2) : 0;
            $isRegistered = true;
        }
    } elseif ($_POST['action'] === 'cancel') {
        // Отмена регистрации
        if (isset($_SESSION['user_id']) && $isRegistered) {
            $stmt = $pdo->prepare("UPDATE registrations SET status = 'cancelled' WHERE user_id = ? AND event_id = ?");
            $stmt->execute([$_SESSION['user_id'], $id]);

            $stmt = $pdo->prepare("UPDATE events SET current_participants = GREATEST(current_participants - 1, 0) WHERE id = ?");
            $stmt->execute([$id]);

            $message = 'Регистрация отменена';
            $messageType = 'success';
            $current = max($current - 1, 0);
            $percent = $max > 0 ? round(($current / $max) * 100, 2) : 0;
            $isRegistered = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']) ?> — ЭкоГород</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #1a1a1a; background: #f9fafb; }
        
        :root {
            --forest-50: #f0fdf4; --forest-100: #dcfce7; --forest-200: #bbf7d0;
            --forest-500: #10b981; --forest-600: #059669; --forest-700: #047857;
            --forest-800: #065f46;
        }

        .container { max-width: 800px; margin: 0 auto; padding: 2rem 1rem; }

        /* Навигация */
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 50; background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: 4rem; display: flex; align-items: center; padding: 0 1rem; }
        .nav-inner { width: 100%; max-width: 800px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { display: flex; align-items: center; gap: 0.5rem; font-family: 'Playfair Display', serif; font-size: 1.125rem; font-weight: 700; color: #111827; }
        .nav-logo-icon { width: 2rem; height: 2rem; border-radius: 0.5rem; background: var(--forest-600); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.75rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; border-radius: 9999px; padding: 0.5rem 1.25rem; transition: 0.2s; cursor: pointer; border: none; font-size: 0.875rem; text-decoration: none; }
        .btn-primary { background: var(--forest-600); color: #fff; }
        .btn-primary:hover { background: var(--forest-700); }
        .btn-outline { background: #fff; color: var(--forest-600); border: 1px solid var(--forest-600); }
        .btn-outline:hover { background: var(--forest-50); }
        .btn-danger { background: #fff; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fef2f2; }
        .btn-disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
        .btn-disabled:hover { background: #f3f4f6; }

        .main-content { padding-top: 5rem; }

        .event-header { margin-bottom: 2rem; }
        .event-header img { width: 100%; max-height: 400px; object-fit: cover; border-radius: 1rem; margin-bottom: 1.5rem; }
        .event-badge { display: inline-block; background: #16a34a; color: #fff; font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.75rem; border-radius: 9999px; margin-bottom: 1rem; }
        .event-header h1 { font-family: 'Playfair Display', serif; font-size: 2rem; color: #111827; margin-bottom: 1rem; }
        @media (min-width: 768px) { .event-header h1 { font-size: 2.5rem; } }
        .event-meta { display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem; color: #6b7280; font-size: 0.9375rem; }
        .event-meta-item { display: flex; align-items: center; gap: 0.5rem; }
        .event-meta-item svg { width: 1.25rem; height: 1.25rem; color: #16a34a; flex-shrink: 0; }
        .event-description { background: #fff; padding: 2rem; border-radius: 1rem; margin-bottom: 1.5rem; line-height: 1.7; }
        .event-info { background: #fff; padding: 1.5rem 2rem; border-radius: 1rem; margin-bottom: 1.5rem; }
        .event-info h3 { font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; }
        .progress-header { display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9375rem; }
        .progress-bar { width: 100%; height: 0.5rem; background: #f3f4f6; border-radius: 9999px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 9999px; background: #16a34a; transition: width 0.5s; }
        .organizer-info { margin-top: 1rem; color: #6b7280; font-size: 0.875rem; }
        .organizer-info strong { color: #111827; }

        .alert { padding: 0.75rem 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.9375rem; }
        .alert-success { background: #f0fdf4; color: #15803d; }
        .alert-error { background: #fef2f2; color: #dc2626; }

        .actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem; }
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: #16a34a; font-weight: 500; text-decoration: none; margin-top: 2rem; }
        .btn-back:hover { text-decoration: underline; }
        .btn-back svg { width: 1.25rem; height: 1.25rem; }
    </style>
</head>
<body>

<!-- Навигация -->
<nav class="navbar">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon">
                <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8.5c0 4.5-6 9.5-6 9.5s-6-5-6-9.5a6 6 0 1 1 12 0Z"/><circle cx="12" cy="8.5" r="2.5"/></svg>
            </div>
            ЭкоГород
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/profile.php" class="btn btn-outline">Личный кабинет</a>
        <?php else: ?>
            <a href="/login.php?redirect=<?= urlencode('/events/1.php?id=' . $id) ?>" class="btn btn-primary">Войти</a>
        <?php endif; ?>
    </div>
</nav>

<div class="main-content">
    <div class="container">
        <!-- Сообщения -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="event-header">
            <?php if ($event['image_url']): ?>
                <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
            <?php endif; ?>
            <span class="event-badge"><?= htmlspecialchars($event['category'] ?? 'Мероприятие') ?></span>
            <h1><?= htmlspecialchars($event['title']) ?></h1>
            <div class="event-meta">
                <div class="event-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span><?= date('d.m.Y, H:i', strtotime($event['event_date'])) ?></span>
                </div>
                <div class="event-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 8.5c0 4.5-6 9.5-6 9.5s-6-5-6-9.5a6 6 0 1 1 12 0Z"/>
                        <circle cx="12" cy="8.5" r="2.5"/>
                    </svg>
                    <span><?= htmlspecialchars($event['location']) ?></span>
                </div>
            </div>
        </div>

        <div class="event-description">
            <p><?= nl2br(htmlspecialchars($event['description'] ?? 'Описание пока не добавлено')) ?></p>
        </div>

        <div class="event-info">
            <h3>Участники</h3>
            <div class="progress-header">
                <span>Зарегистрировано</span>
                <span><strong><?= $current ?></strong> из <?= $max ?></span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $percent ?>%;"></div>
            </div>
            <p class="organizer-info">
                Организатор: <strong><?= htmlspecialchars($event['organizer'] ?? 'Не указан') ?></strong>
            </p>

            <!-- Кнопки действий -->
            <div class="actions">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- Не авторизован -->
                    <a href="/login.php?redirect=<?= urlencode('/events/1.php?id=' . $id) ?>" class="btn btn-primary">
                        Войти, чтобы зарегистрироваться
                    </a>
                <?php elseif ($isRegistered): ?>
                    <!-- Уже зарегистрирован -->
                    <span class="btn btn-disabled">✓ Вы зарегистрированы</span>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit" class="btn btn-danger">Отменить регистрацию</button>
                    </form>
                <?php elseif ($current >= $max): ?>
                    <!-- Мест нет -->
                    <span class="btn btn-disabled">Мест нет</span>
                <?php else: ?>
                    <!-- Можно зарегистрироваться -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="register">
                        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <a href="/" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Вернуться на главную
        </a>
    </div>
</div>

</body>
</html>