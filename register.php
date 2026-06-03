<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Валидация
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Заполните все обязательные поля';
    } elseif (strlen($name) < 2) {
        $error = 'Имя должно содержать минимум 2 символа';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный email';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен содержать минимум 6 символов';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают';
    } else {
        // Проверяем, не занят ли email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким email уже существует';
        } else {
            // Хешируем пароль
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Сохраняем пользователя
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
            $stmt->execute([$name, $email, $hashedPassword]);
            
            // Получаем ID нового пользователя
            $userId = $pdo->lastInsertId();
            
            // Авторизуем пользователя
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            
            // Редирект в личный кабинет
            header('Location: /profile.php');
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
    <title>Регистрация — ЭкоГород</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #f0fdf4; line-height: 1.5; min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; color: inherit; }

        :root {
            --forest-50: #f0fdf4; --forest-100: #dcfce7; --forest-200: #bbf7d0;
            --forest-500: #10b981; --forest-600: #059669; --forest-700: #047857;
            --forest-800: #065f46; --forest-900: #064e3b;
        }

/* ===== НАВИГАЦИЯ ===== */
.navbar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 50;
    background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.nav-inner {
    width: 100%; max-width: 1280px; margin: 0 auto;
    padding: 0 1rem; height: 4rem;
    display: flex; align-items: center; justify-content: space-between;
}
@media (min-width: 640px) { .nav-inner { padding: 0 1.5rem; } }
@media (min-width: 768px) { .nav-inner { height: 5rem; } }
@media (min-width: 1024px) { .nav-inner { padding: 0 3rem; } }

/* Логотип */
.header-logo {
    display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;
    text-decoration: none; color: inherit;
}
.header-logo-icon {
    width: 2rem; height: 2rem; border-radius: 0.5rem;
    background: var(--forest-600);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
@media (min-width: 768px) {
    .header-logo-icon { width: 2.25rem; height: 2.25rem; }
}
.header-logo-icon img {
    width: 100%; height: 100%; object-fit: contain;
}
.logo-text {
    font-family: 'Playfair Display', serif;
    font-size: 1.125rem; font-weight: 700; color: #111827;
}
@media (min-width: 768px) { .logo-text { font-size: 1.25rem; } }

/* Ссылки */
.nav-links { display: none; gap: 2rem; }
@media (min-width: 768px) { .nav-links { display: flex; } }
.nav-links a {
    font-size: 0.875rem; font-weight: 500; color: #4b5563;
    transition: color 0.2s; white-space: nowrap; text-decoration: none;
}
.nav-links a:hover { color: var(--forest-600); }
.nav-links a.active { color: var(--forest-600); font-weight: 600; }

/* Действия */
.nav-actions { display: none; align-items: center; gap: 0.75rem; }
@media (min-width: 768px) { .nav-actions { display: flex; } }
.nav-user-name {
    font-size: 0.875rem; font-weight: 500; color: #374151;
    white-space: nowrap; max-width: 120px; overflow: hidden; text-overflow: ellipsis;
}

/* Кнопки */
.btn {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 0.5rem; font-weight: 500; border-radius: 9999px;
    transition: all 0.2s; cursor: pointer; white-space: nowrap;
    border: none; font-size: 0.875rem; padding: 0.5rem 1.25rem;
    text-decoration: none;
}
.btn-primary { background: var(--forest-600); color: #fff; }
.btn-primary:hover { background: var(--forest-700); }
.btn-ghost { background: transparent; color: #4b5563; }
.btn-ghost:hover { background: #f3f4f6; }

/* Бургер */
.header-burger {
    display: flex; align-items: center; justify-content: center;
    width: 2.5rem; height: 2.5rem; background: none; border: none;
    cursor: pointer; color: #111827;
}
@media (min-width: 768px) { .header-burger { display: none; } }

/* Мобильное меню */
.mobile-menu {
    background: #fff; border-top: 1px solid #f3f4f6;
    padding: 0.5rem 1rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}
.mobile-link {
    display: block; padding: 0.75rem 1rem; border-radius: 0.5rem;
    font-size: 0.9375rem; font-weight: 500; color: #374151;
    text-decoration: none; transition: background 0.2s;
}
.mobile-link:hover { background: #f3f4f6; }
.mobile-link-highlight {
    background: var(--forest-600); color: #fff; text-align: center;
    border-radius: 0.75rem; margin-top: 0.25rem;
}
.mobile-link-highlight:hover { background: var(--forest-700); }

        .main-content { flex: 1; display: flex; align-items: center; justify-content: center; padding: 6rem 1rem 3rem; }
        @media (min-width: 768px) { .main-content { padding-top: 7rem; } }

        .register-card { background: #fff; border-radius: 1.5rem; padding: 2rem; border: 1px solid #f3f4f6; box-shadow: 0 1px 3px rgba(0,0,0,0.05); width: 100%; max-width: 28rem; }
        @media (min-width: 768px) { .register-card { padding: 2.5rem; } }

        .register-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: var(--forest-50); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--forest-600); font-size: 1.25rem; }
        .register-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 0.25rem; }
        @media (min-width: 768px) { .register-title { font-size: 1.75rem; } }
        .register-subtitle { text-align: center; font-size: 0.875rem; color: #6b7280; margin-bottom: 2rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem; }
        .form-input { width: 100%; padding: 0.75rem 1rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #34d399; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

        .form-error { background: #fef2f2; color: #dc2626; font-size: 0.8125rem; padding: 0.75rem 1rem; border-radius: 0.75rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .form-error i { flex-shrink: 0; }

        .form-hint { font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem; }

        .btn-submit { width: 100%; padding: 0.875rem; background: var(--forest-600); color: #fff; border: none; border-radius: 9999px; font-size: 0.9375rem; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--forest-700); }

        .register-footer { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #6b7280; }
        .register-footer a { color: var(--forest-600); font-weight: 500; transition: color 0.2s; }
        .register-footer a:hover { color: var(--forest-700); }

        .footer { background: var(--forest-900); color: #fff; padding: 3rem 0 0; margin-top: auto; }
        .footer-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2.5rem; max-width: 1280px; margin: 0 auto; padding: 0 1rem; }
        @media (min-width: 768px) { .footer-grid { grid-template-columns: 2fr 1fr 1fr 1fr 1fr; } }
        .footer-col h4 { font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 0.625rem; }
        .footer-col ul a { font-size: 0.875rem; color: var(--forest-200); transition: 0.2s; }
        .footer-col ul a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid var(--forest-800); margin-top: 3rem; padding: 1.25rem 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; font-size: 0.75rem; color: var(--forest-200); max-width: 1280px; margin: 0 auto; padding: 1.25rem 1rem; }
        .socials { display: flex; gap: 0.75rem; }
        .socials a { width: 2.25rem; height: 2.25rem; border: 1px solid var(--forest-800); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--forest-200); transition: 0.2s; }
        .socials a:hover { color: #fff; border-color: var(--forest-700); }

        @media (min-width: 640px) {
            .footer-grid { padding: 0 1.5rem; }
            .footer-bottom { padding: 1.25rem 1.5rem; }
        }
        @media (min-width: 1024px) {
            .footer-grid { padding: 0 3rem; }
            .footer-bottom { padding: 1.25rem 3rem; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <!-- Логотип -->
        <a href="/index.php" class="header-logo">
            <div class="header-logo-icon">
                <img src="/img/logo.png" alt="ЭкоГород" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <span class="logo-text">ЭкоГород</span>
        </a>

        <!-- Навигационные ссылки (десктоп) -->
        <div class="nav-links">
            <a href="/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Главная</a>
            <a href="/events.php" class="<?= basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : '' ?>">Мероприятия</a>
            <a href="/about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">О проекте</a>
        </div>

        <!-- Кнопки действий (десктоп) -->
        <div class="nav-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Если пользователь авторизован -->
                <a href="/profile.php" class="btn btn-ghost" title="Личный кабинет">
                    <svg width="1.25rem" height="1.25rem" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </a>
                <span class="nav-user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="/logout.php" class="btn btn-ghost">Выйти</a>
            <?php else: ?>
                <!-- Если не авторизован -->
                <a href="/login.php" class="btn btn-ghost">Войти</a>
                <a href="/register.php" class="btn btn-primary">Регистрация</a>
            <?php endif; ?>
        </div>

        <!-- Бургер-меню (мобильные) -->
        <button class="header-burger" id="burgerBtn" aria-label="Открыть меню">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>

    <!-- Мобильное меню (скрыто по умолчанию) -->
    <div class="mobile-menu" id="mobileMenu" style="display: none;">
        <a href="/index.php" class="mobile-link">Главная</a>
        <a href="/events.php" class="mobile-link">Мероприятия</a>
        <a href="/about.php" class="mobile-link">О проекте</a>
        <hr style="border-color: #e5e7eb; margin: 0.5rem 0;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/profile.php" class="mobile-link">Личный кабинет</a>
            <a href="/logout.php" class="mobile-link">Выйти</a>
        <?php else: ?>
            <a href="/login.php" class="mobile-link">Войти</a>
            <a href="/register.php" class="mobile-link mobile-link-highlight">Регистрация</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Основной контент -->
<div class="main-content">
    <div class="register-card">
        <div class="register-icon">
            <i class="ri-user-add-line"></i>
        </div>
        <h1 class="register-title">Регистрация</h1>
        <p class="register-subtitle">Создайте аккаунт для участия в мероприятиях</p>

        <?php if ($error): ?>
            <div class="form-error">
                <i class="ri-error-warning-line"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label" for="name">Имя *</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Ваше имя" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                <p class="form-hint">Минимум 2 символа</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Пароль *</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Минимум 6 символов" required>
                <p class="form-hint">Минимум 6 символов</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirm">Подтверждение пароля *</label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-input" placeholder="Повторите пароль" required>
            </div>

            <button type="submit" class="btn-submit">Зарегистрироваться</button>
        </form>

        <div class="register-footer">
            Уже есть аккаунт? <a href="/login.php">Войти</a>
        </div>
    </div>
</div>

<!-- Футер -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <a href="/" class="header-logo" style="margin-bottom:1rem;">
                <div class="header-logo-icon"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8.5c0 4.5-6 9.5-6 9.5s-6-5-6-9.5a6 6 0 1 1 12 0Z"/><circle cx="12" cy="8.5" r="2.5"/></svg></div>
                <span class="logo-text" style="color:#fff;">ЭкоГород</span>
            </a>
            <p style="font-size:0.875rem;color:var(--forest-200);line-height:1.6;">Платформа для объединения горожан вокруг экологических инициатив.</p>
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
                <li><a href="/login.php">Личный кабинет</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Организаторам</h4>
            <ul>
                <li><a href="/admin">Создать мероприятие</a></li>
                <li><a href="/about.php">Партнерство</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Контакты</h4>
            <ul>
                <li><a href="mailto:info@ecogorod.ru">info@ecogorod.ru</a></li>
                <li><a href="tel:+79991234567">+7 (999) 123-45-67</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 ЭкоГород. Все права защищены.</p>
        <div class="socials">
            <a href="#"><i class="ri-vk-line"></i></a>
            <a href="#"><i class="ri-telegram-line"></i></a>
            <a href="#"><i class="ri-youtube-line"></i></a>
            <a href="#"><i class="ri-instagram-line"></i></a>
        </div>
        <a href="/about.php" style="color:inherit;">Политика конфиденциальности</a>
    </div>
</footer>

</body>
</html>