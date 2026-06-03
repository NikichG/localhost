<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        // Ищем пользователя по email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Проверяем пароль (хеширование)
        if ($user && password_verify($password, $user['password']))  {
            // Успешный вход
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            header('Location: /profile.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в аккаунт — ЭкоГород</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #f0fdf4; line-height: 1.5; min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; color: inherit; }

        :root {
            --forest-50: #f0fdf4; --forest-100: #dcfce7; --forest-200: #bbf7d0;
            --forest-500: #10b981; --forest-600: #059669; --forest-700: #047857;
            --forest-800: #065f46; --forest-900: #064e3b;
        }

        /* Шапка */
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
        /* Основной контент */
        .main-content { flex: 1; display: flex; align-items: center; justify-content: center; padding: 6rem 1rem 3rem; }
        @media (min-width: 768px) { .main-content { padding-top: 7rem; } }

        /* Карточка формы */
        .login-card { background: #fff; border-radius: 1.5rem; padding: 2rem; border: 1px solid #f3f4f6; box-shadow: 0 1px 3px rgba(0,0,0,0.05); width: 100%; max-width: 28rem; }
        @media (min-width: 768px) { .login-card { padding: 2.5rem; } }

        .login-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: var(--forest-50); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--forest-600); font-size: 1.25rem; }
        .login-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 0.25rem; }
        @media (min-width: 768px) { .login-title { font-size: 1.75rem; } }
        .login-subtitle { text-align: center; font-size: 0.875rem; color: #6b7280; margin-bottom: 2rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem; }
        .form-input { width: 100%; padding: 0.75rem 1rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #34d399; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

        .form-error { background: #fef2f2; color: #dc2626; font-size: 0.8125rem; padding: 0.75rem 1rem; border-radius: 0.75rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .form-error i { flex-shrink: 0; }

        .form-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; margin-bottom: 1.5rem; }
        .form-checkbox { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #6b7280; }
        .form-checkbox input { width: 1rem; height: 1rem; accent-color: var(--forest-600); }
        .form-link { color: var(--forest-600); font-weight: 500; transition: color 0.2s; }
        .form-link:hover { color: var(--forest-700); }

        .btn-submit { width: 100%; padding: 0.875rem; background: var(--forest-600); color: #fff; border: none; border-radius: 9999px; font-size: 0.9375rem; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--forest-700); }

        .login-footer { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #6b7280; }
        .login-footer a { color: var(--forest-600); font-weight: 500; transition: color 0.2s; }
        .login-footer a:hover { color: var(--forest-700); }

        /* ---------- FOOTER ---------- */
        .footer { background: var(--forest-900); color: #fff; }
        .footer-main { padding: 3rem 0; }
        @media (min-width: 768px) { .footer-main { padding: 4rem 0; } }
        .footer-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; }
        @media (min-width: 768px) { .footer-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .footer-grid { grid-template-columns: 2fr 1fr 1fr 1fr 1fr; } }
        .footer-col--brand { max-width: 20rem; }
        .footer-logo { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
        .footer-logo-icon { width: 2rem; height: 2rem; border-radius: 0.5rem; background: var(--forest-500); display: flex; align-items: center; justify-content: center; font-size: 0.875rem; color: #fff; }
        .footer-logo-text { font-size: 1.125rem; font-weight: 700; }
        .footer-description { font-size: 0.875rem; color: var(--forest-200); line-height: 1.6; }
        .footer-heading { font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.625rem; }
        .footer-links a { font-size: 0.875rem; color: var(--forest-200); transition: color 0.2s; }
        .footer-links a:hover { color: #fff; }
        .footer-contact-icons { display: flex; gap: 0.75rem; align-items: center; }
        .footer-icon-link { width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: var(--forest-800); display: flex; align-items: center; justify-content: center; color: var(--forest-300); font-size: 1.25rem; transition: all 0.2s ease; }
        .footer-icon-link:hover { background: var(--forest-700); color: #fff; }
        .footer-bottom { border-top: 1px solid var(--forest-800); }
        .footer-bottom-inner { display: flex; flex-direction: column; align-items: center; gap: 1rem; padding: 1.25rem 0; }
        @media (min-width: 640px) { .footer-bottom-inner { flex-direction: row; justify-content: space-between; } }
        .footer-copyright { font-size: 0.75rem; color: var(--forest-300); }
        .footer-socials { display: flex; gap: 0.75rem; }
        .footer-social-link { width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; border: 1px solid var(--forest-700); display: flex; align-items: center; justify-content: center; color: var(--forest-300); transition: all 0.2s; }
        .footer-social-link svg { width: 0.875rem; height: 0.875rem; fill: currentColor; }
        .footer-social-link:hover { color: #fff; border-color: var(--forest-500); }
        .footer-privacy { font-size: 0.75rem; color: var(--forest-300); transition: color 0.2s; }
        .footer-privacy:hover { color: #fff; }

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
    <div class="login-card">
        <div class="login-icon">
            <i class="ri-leaf-line"></i>
        </div>
        <h1 class="login-title">Вход в аккаунт</h1>
        <p class="login-subtitle">Войдите, чтобы записываться на мероприятия</p>

        <?php if ($error): ?>
            <div class="form-error">
                <i class="ri-error-warning-line"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Введите пароль" required>
            </div>

            <div class="form-row">
                <label class="form-checkbox">
                    <input type="checkbox" name="remember">
                    <span>Запомнить меня</span>
                </label>
                <a href="/forgot-password.php" class="form-link">Забыли пароль?</a>
            </div>

            <button type="submit" class="btn-submit">Войти</button>
        </form>

        <div class="login-footer">
            Нет аккаунта? <a href="/register.php">Зарегистрироваться</a>
        </div>
    </div>
</div>

    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    
                    <!-- Логотип и описание -->
                    <div class="footer-col footer-col--brand">
    <a href="/" class="footer-logo">
        <div class="footer-logo-icon">
            <img src="/img/logo.png" alt="ЭкоГород" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <span class="footer-logo-text font-display">ЭкоГород</span>
    </a>
    <!-- остальное -->
    </div>

                    <!-- Платформа -->
                    <div class="footer-col">
                        <h4 class="footer-heading">Платформа</h4>
                        <ul class="footer-links">
                            <li><a href="/">Главная</a></li>
                            <li><a href="/events">Мероприятия</a></li>
                            <li><a href="/about">О проекте</a></li>
                        </ul>
                    </div>

                    <!-- Участникам -->
                    <div class="footer-col">
                        <h4 class="footer-heading">Участникам</h4>
                        <ul class="footer-links">
                            <li><a href="/about">Как записаться</a></li>
                            <li><a href="/about">Правила участия</a></li>
                            <li><a href="/login">Личный кабинет</a></li>
                        </ul>
                    </div>

                    <!-- Организаторам -->
                    <div class="footer-col">
                        <h4 class="footer-heading">Организаторам</h4>
                        <ul class="footer-links">
                            <li><a href="/admin">Создать мероприятие</a></li>
                            <li><a href="/about">Партнерство</a></li>
                        </ul>
                    </div>

                    <!-- Контакты -->
                    <div class="footer-col">
                        <h4 class="footer-heading">Контакты</h4>
                        <ul class="footer-links">
                            <li><a href="mailto:info@ecogorod.ru">info@ecogorod.ru</a></li>
                            <li><a href="tel:+79991234567">+7 (999) 123-45-67</a></li>
                            <li><a href="/">Telegram-канал</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p class="footer-copyright">© 2026 ЭкоГород. Все права защищены.</p>
                <div class="footer-socials">
                    <a href="#" class="footer-social-link" aria-label="VK">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.68 13.74c1.08 1.07 2.22 2.07 2.85 3.44.19.41.19.41-.23.41h-2.08c-.52 0-.72-.22-1.04-.64-.55-.7-1.19-1.44-1.79-1.9-.35-.3-.51-.22-.51.22v1.36c0 .57-.19.73-.64.73-1.22.07-2.48-.08-3.6-.95-1.29-1.02-2.19-2.57-3.05-4.11C4.9 10.78 4.24 9.29 3.6 7.78c-.14-.35-.03-.52.38-.52h2.08c.34 0 .5.16.64.44.68 1.44 1.56 2.76 2.46 3.97.23.31.44.44.6.12.16-.32.08-1.13.05-1.75-.03-.66-.23-1.02-.64-1.18-.2-.08-.12-.33.08-.49.36-.26.94-.29 1.57-.27.79.02 1.03.22 1.13.75.13.68.1 1.58.22 2.25.07.38.3.47.5.23.52-.61.97-1.29 1.38-2.01.16-.28.27-.44.68-.44h2.17c.46 0 .58.21.47.52-.22.66-1.22 1.85-1.92 2.73-.42.52-.44.73-.03 1.13z"/></svg>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="Telegram">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.66-.52.36-1 .53-1.42.52-.47-.01-1.37-.26-2.03-.48-.82-.27-1.47-.42-1.41-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.63-2.87 7.95-3.42 3.78-1.57 4.57-1.84 5.08-1.85.11 0 .37.03.54.17.14.12.18.28.2.45.02.17.01.34.01.34z"/></svg>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
                <a href="/about" class="footer-privacy">Политика конфиденциальности</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>