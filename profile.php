<?php
session_start();
require_once __DIR__ . '/db.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// Определяем активную вкладку (ДОЛЖНО БЫТЬ ЗДЕСЬ)
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'events';

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // Если пользователь не найден, разлогиниваем
    session_destroy();
    header('Location: /login.php');
    exit;
}

// Получаем мероприятия пользователя (заглушка — нужно создать таблицу registrations)
$stmt = $pdo->prepare("
    SELECT e.*, r.status 
    FROM events e 
    JOIN registrations r ON e.id = r.event_id 
    WHERE r.user_id = ? 
    ORDER BY e.event_date DESC
");
$stmt->execute([$user['id']]);
$myEvents = $stmt->fetchAll();

// Разделяем на предстоящие и прошедшие
$upcomingEvents = array_filter($myEvents, function($e) {
    return strtotime($e['event_date']) >= time();
});
$pastEvents = array_filter($myEvents, function($e) {
    return strtotime($e['event_date']) < time();
});

// Считаем статистику
$totalEvents = count($myEvents);
$totalHours = $totalEvents * 3; // Примерно 3 часа на мероприятие
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет — ЭкоГород</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #f0fdf4; line-height: 1.5; min-height: 100vh; }
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
        .nav-links { display: none; gap: 2rem; }
        @media (min-width: 768px) { .nav-links { display: flex; } }
        .nav-links a { font-size: 0.875rem; font-weight: 500; color: #4b5563; transition: 0.2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--forest-600); }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; border-radius: 9999px; padding: 0.5rem 1.25rem; transition: 0.2s; cursor: pointer; border: none; font-size: 0.875rem; }
        .btn-ghost { background: transparent; color: #4b5563; }
        .btn-ghost:hover { background: #f3f4f6; }
        .btn-primary { background: var(--forest-600); color: #fff; }
        .btn-primary:hover { background: var(--forest-700); }

        .container { max-width: 1280px; margin: 0 auto; padding: 0 1rem; }

        .main-content { padding: 6rem 0 3rem; }
        @media (min-width: 768px) { .main-content { padding-top: 7rem; } }

        /* Профиль */
        .profile-header { background: #fff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #f3f4f6; margin-bottom: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 1.25rem; }
        @media (min-width: 640px) { .profile-header { flex-direction: row; } }
        .profile-avatar { width: 5rem; height: 5rem; border-radius: 50%; background: var(--forest-100); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--forest-600); flex-shrink: 0; }
        .profile-info { flex: 1; text-align: center; }
        @media (min-width: 640px) { .profile-info { text-align: left; } }
        .profile-name { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
        .profile-email { font-size: 0.875rem; color: #6b7280; margin-bottom: 0.75rem; }
        .profile-badges { display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; }
        @media (min-width: 640px) { .profile-badges { justify-content: flex-start; } }
        .badge { font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; background: #f3f4f6; color: #4b5563; }
        .badge-green { background: var(--forest-50); color: var(--forest-700); }
        .profile-stats { display: flex; gap: 2rem; }
        .stat-item { text-align: center; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--forest-600); }
        .stat-label { font-size: 0.75rem; color: #6b7280; margin-top: 0.125rem; }

        /* Сетка */
        .grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 1024px) { .grid { grid-template-columns: 240px 1fr; } }

        /* Боковое меню */
        .sidebar { background: #fff; border-radius: 1rem; border: 1px solid #f3f4f6; overflow: hidden; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #4b5563; transition: 0.2s; border-left: 4px solid transparent; }
        .sidebar-link:hover { background: #f9fafb; }
        .sidebar-link.active { background: #ecfdf5; color: #047857; border-left-color: #10b981; }

        /* Карточки мероприятий */
        .section-card { background: #fff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #f3f4f6; margin-bottom: 1.5rem; }
        .section-title { font-weight: 600; font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .event-item { display: flex; gap: 1rem; padding: 1rem; border: 1px solid #f3f4f6; border-radius: 0.75rem; margin-bottom: 1rem; transition: 0.2s; }
        .event-item:hover { border-color: var(--forest-200); }
        .event-item img { width: 8rem; height: 6rem; border-radius: 0.5rem; object-fit: cover; flex-shrink: 0; }
        @media (min-width: 640px) { .event-item img { width: 10rem; height: 6rem; } }
        .event-info { flex: 1; }
        .event-info h4 { font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem; }
        .event-info p { font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem; }
        .event-info .actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .event-info .actions a, .event-info .actions button { font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.75rem; border-radius: 0.5rem; cursor: pointer; transition: 0.2s; background: none; border: none; }
        .action-details { color: var(--forest-600); }
        .action-details:hover { color: var(--forest-700); }
        .action-cancel { color: #ef4444; }
        .action-cancel:hover { color: #dc2626; }
        .action-review { color: var(--forest-600); }
        .action-review:hover { color: var(--forest-700); }
        .status { font-size: 0.625rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-weight: 500; }
        .status-confirmed { background: #f0fdf4; color: #15803d; }
        .status-pending { background: #fffbeb; color: #b45309; }
        .status-done { background: #f3f4f6; color: #6b7280; }

        .empty-state { text-align: center; padding: 2rem; color: #9ca3af; }
        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.5rem; }

        /* Футер */
        .footer { background: var(--forest-900); color: #fff; padding: 3rem 0 0; margin-top: 3rem; }
        .footer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; }
        @media (min-width: 768px) { .footer-grid { grid-template-columns: 2fr 1fr 1fr 1fr 1fr; } }
        .footer-col h4 { font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 0.625rem; }
        .footer-col ul a { font-size: 0.875rem; color: var(--forest-200); transition: 0.2s; }
        .footer-col ul a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid var(--forest-800); margin-top: 3rem; padding: 1.25rem 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; font-size: 0.75rem; color: var(--forest-200); }
        .socials { display: flex; gap: 0.75rem; }
        .socials a { width: 2.25rem; height: 2.25rem; border: 1px solid var(--forest-800); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--forest-200); transition: 0.2s; }
        .socials a:hover { color: #fff; border-color: var(--forest-700); }

        @media (min-width: 640px) { .container { padding: 0 1.5rem; } }
        @media (min-width: 1024px) { .container { padding: 0 3rem; } }
    </style>
</head>
<body>

<!-- Навигация -->
<nav class="navbar">
    <div class="nav-inner">
        <a href="/" class="header-logo">
            <div class="header-logo-icon">
                <img src="/img/logo.png" alt="ЭкоГород">
            </div>
            <span class="logo-text">ЭкоГород</span>
        </a>
        <div class="nav-links">
            <a href="/">Главная</a>
            <a href="/events.php">Мероприятия</a>
            <a href="/about.php">О проекте</a>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size:0.875rem;color:#374151;"><?= htmlspecialchars($user['name']) ?></span>
            <a href="/logout.php" class="btn btn-ghost">Выйти</a>
        </div>
    </div>
</nav>

<!-- Основной контент -->
<div class="main-content">
    <div class="container">
        <!-- Профиль -->
        <div class="profile-header">
            <div class="profile-avatar">
                <?= mb_substr($user['name'], 0, 1) ?>
            </div>
            <div class="profile-info">
                <h1 class="profile-name"><?= htmlspecialchars($user['name']) ?></h1>
                <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
                <div class="profile-badges">
                    <span class="badge badge-green">🌱 Эко-активист</span>
                    <span class="badge">📍 Москва</span>
                    <span class="badge">📅 С <?= date('Y', strtotime($user['created_at'])) ?> года</span>
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-value"><?= $totalEvents ?></div>
                    <div class="stat-label">Мероприятий</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= $totalHours ?></div>
                    <div class="stat-label">Часов</div>
                </div>
            </div>
        </div>

        <!-- Сетка -->
        <div class="grid">
            <!-- Боковое меню -->
            <div class="sidebar">
                <a href="/profile.php?tab=events" class="sidebar-link <?= $tab == 'events' ? 'active' : '' ?>">
            <i class="ri-calendar-event-line"></i> Мои мероприятия
                </a>
                <a href="/create-event.php" class="sidebar-link" style="color:var(--forest-600); font-weight:600;">
        <i class="ri-add-circle-line"></i> Создать мероприятие
    </a>
    <a href="/profile.php?tab=stats" class="sidebar-link <?= $tab == 'stats' ? 'active' : '' ?>">
        <i class="ri-bar-chart-box-line"></i> Статистика
    </a>
    <a href="/profile.php?tab=settings" class="sidebar-link <?= $tab == 'settings' ? 'active' : '' ?>">
        <i class="ri-settings-4-line"></i> Настройки
    </a>
</div>

            <!-- Основная область -->
            <div>
                <?php if ($tab == 'events'): ?>
                    <!-- Предстоящие мероприятия -->
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="ri-calendar-check-line" style="color:var(--forest-500);"></i>
                            Предстоящие мероприятия
                            <span style="margin-left:auto;font-size:0.75rem;background:var(--forest-50);color:var(--forest-700);padding:0.25rem 0.625rem;border-radius:9999px;"><?= count($upcomingEvents) ?></span>
                        </h3>
                        
                        <?php if (empty($upcomingEvents)): ?>
                            <div class="empty-state">
                                <i class="ri-calendar-line"></i>
                                <p>Нет предстоящих мероприятий</p>
                                <a href="/events.php" style="color:var(--forest-600);font-size:0.875rem;">Найти мероприятия</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($upcomingEvents as $event): ?>
                                <div class="event-item">
                                    <img src="<?= htmlspecialchars($event['image_url'] ?? 'https://readdy.ai/api/search-image?query=eco%20event&width=400&height=240') ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                                    <div class="event-info">
                                        <h4><?= htmlspecialchars($event['title']) ?></h4>
                                        <p><i class="ri-time-line"></i> <?= date('d.m.Y, H:i', strtotime($event['event_date'])) ?></p>
                                        <span class="status <?= $event['status'] == 'confirmed' ? 'status-confirmed' : 'status-pending' ?>">
                                            <?= $event['status'] == 'confirmed' ? 'Подтверждено' : 'Ожидание' ?>
                                        </span>
                                        <div class="actions" style="margin-top:0.5rem;">
                                            <a href="/events/1.php?id=<?= $event['id'] ?>" class="action-details">Подробнее</a>
                                            <span style="color:#d1d5db;">·</span>
                                            <a href="/cancel.php?id=<?= $event['id'] ?>" class="action-cancel">Отменить запись</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Прошедшие мероприятия -->
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="ri-history-line" style="color:var(--forest-500);"></i>
                            Прошедшие мероприятия
                        </h3>
                        
                        <?php if (empty($pastEvents)): ?>
                            <div class="empty-state">
                                <i class="ri-history-line"></i>
                                <p>Нет прошедших мероприятий</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($pastEvents as $event): ?>
                                <div class="event-item" style="opacity:0.75;">
                                    <img src="<?= htmlspecialchars($event['image_url'] ?? 'https://readdy.ai/api/search-image?query=eco%20event&width=400&height=240') ?>" alt="<?= htmlspecialchars($event['title']) ?>" style="filter:grayscale(100%);">
                                    <div class="event-info">
                                        <h4><?= htmlspecialchars($event['title']) ?></h4>
                                        <p><i class="ri-time-line"></i> <?= date('d.m.Y', strtotime($event['event_date'])) ?></p>
                                        <span class="status status-done">Посещено</span>
                                        <div class="actions" style="margin-top:0.5rem;">
                                            <a href="/review.php?id=<?= $event['id'] ?>" class="action-review">Оставить отзыв</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                <?php elseif ($tab == 'stats'): ?>
                    <!-- Статистика -->
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="ri-bar-chart-box-line" style="color:var(--forest-500);"></i>
                            Моя статистика
                        </h3>
                        
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                            <div style="background:#f0fdf4; padding:1.5rem; border-radius:0.75rem; text-align:center;">
                                <div style="font-size:2rem; font-weight:700; color:var(--forest-600);"><?= $totalEvents ?></div>
                                <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Всего мероприятий</div>
                            </div>
                            <div style="background:#f0fdf4; padding:1.5rem; border-radius:0.75rem; text-align:center;">
                                <div style="font-size:2rem; font-weight:700; color:var(--forest-600);"><?= $totalHours ?></div>
                                <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Часов волонтёрства</div>
                            </div>
                            <div style="background:#f0fdf4; padding:1.5rem; border-radius:0.75rem; text-align:center;">
                                <div style="font-size:2rem; font-weight:700; color:var(--forest-600);"><?= count($upcomingEvents) ?></div>
                                <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Предстоящих</div>
                            </div>
                        </div>

                        <h4 style="font-weight:600; margin-bottom:1rem;">Достижения</h4>
                        <div style="display:flex; flex-direction:column; gap:0.75rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; background:#f9fafb; border-radius:0.5rem;">
                                <span style="font-size:1.5rem;">🌱</span>
                                <div>
                                    <div style="font-weight:500;">Новичок</div>
                                    <div style="font-size:0.75rem; color:#6b7280;">1 мероприятие</div>
                                </div>
                                <?php if ($totalEvents >= 1): ?>
                                    <span style="margin-left:auto; color:#10b981;">✓</span>
                                <?php endif; ?>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; background:#f9fafb; border-radius:0.5rem;">
                                <span style="font-size:1.5rem;">🌿</span>
                                <div>
                                    <div style="font-weight:500;">Эко-волонтёр</div>
                                    <div style="font-size:0.75rem; color:#6b7280;">5 мероприятий</div>
                                </div>
                                <?php if ($totalEvents >= 5): ?>
                                    <span style="margin-left:auto; color:#10b981;">✓</span>
                                <?php endif; ?>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; background:#f9fafb; border-radius:0.5rem;">
                                <span style="font-size:1.5rem;">🌳</span>
                                <div>
                                    <div style="font-weight:500;">Эко-лидер</div>
                                    <div style="font-size:0.75rem; color:#6b7280;">10 мероприятий</div>
                                </div>
                                <?php if ($totalEvents >= 10): ?>
                                    <span style="margin-left:auto; color:#10b981;">✓</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($tab == 'settings'): ?>
                    <!-- Настройки -->
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="ri-settings-4-line" style="color:var(--forest-500);"></i>
                            Настройки профиля
                        </h3>
                        
                        <?php if (isset($_GET['saved'])): ?>
                            <div style="background:#f0fdf4; color:#15803d; padding:0.75rem 1rem; border-radius:0.75rem; margin-bottom:1.5rem; font-size:0.875rem;">
                                ✓ Изменения сохранены
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/update-profile.php" style="max-width: 400px;">
                            <div style="margin-bottom:1.25rem;">
                                <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">Имя</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.75rem; font-size:0.875rem; outline:none;" required>
                            </div>
                            <div style="margin-bottom:1.25rem;">
                                <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.75rem; font-size:0.875rem; outline:none;" required>
                            </div>
                            <div style="margin-bottom:1.25rem;">
                                <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">Новый пароль (оставьте пустым, чтобы не менять)</label>
                                <input type="password" name="password" placeholder="Минимум 6 символов" style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.75rem; font-size:0.875rem; outline:none;">
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%; padding:0.875rem; border-radius:9999px;">Сохранить изменения</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Футер -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="/" class="header-logo" style="margin-bottom:1rem;">
                    <div class="header-logo-icon"><img src="/img/logo.png" alt=""></div>
                    <span class="logo-text" style="color:#fff;">ЭкоГород</span>
                </a>
                <p style="font-size:0.875rem;color:var(--forest-200);">Платформа для объединения горожан вокруг экологических инициатив.</p>
            </div>
            <div class="footer-col">
                <h4>Платформа</h4>
                <ul>
                    <li><a href="/">Главная</a></li>
                    <li><a href="/events.php">Мероприятия</a></li>
                    <li><a href="/about.php">О проекте</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Участникам</h4>
                <ul>
                    <li><a href="/about.php">Как записаться</a></li>
                    <li><a href="/profile.php">Личный кабинет</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Контакты</h4>
                <ul>
                    <li><a href="mailto:info@ecogorod.ru">info@ecogorod.ru</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 ЭкоГород. Все права защищены.</p>
            <div class="socials">
                <a href="#"><i class="ri-vk-line"></i></a>
                <a href="#"><i class="ri-telegram-line"></i></a>
            </div>
            <a href="/about.php" style="color:inherit;">Политика конфиденциальности</a>
        </div>
    </div>
</footer>

</body>
</html>