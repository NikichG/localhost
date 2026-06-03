<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О проекте — ЭкоГород</title>
    <meta name="description" content="Узнайте больше о платформе ЭкоГород — миссия, ценности и принципы работы.">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; line-height: 1.5; color: #1a1a1a; background: #fff; -webkit-font-smoothing: antialiased; }
        img { max-width: 100%; display: block; }
        a { text-decoration: none; color: inherit; }

        :root {
            --forest-50: #f0fdf4; --forest-100: #dcfce7; --forest-200: #bbf7d0;
            --forest-300: #86efac; --forest-400: #4ade80; --forest-500: #22c55e;
            --forest-600: #16a34a; --forest-700: #15803d; --forest-800: #166534;
            --forest-900: #14532d; --forest-950: #052e16;
            --accent-green: #6ee7b7;
        }

        .container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 1rem; }
        @media (min-width: 640px) { .container { padding: 0 1.5rem; } }
        @media (min-width: 1024px) { .container { padding: 0 3rem; } }

        .font-display { font-family: 'Playfair Display', serif; }

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
        /* ---------- HERO ABOUT ---------- */
        .about-hero {
            position: relative; min-height: 400px; display: flex; align-items: center; overflow: hidden;
        }
        @media (min-width: 768px) { .about-hero { min-height: 500px; } }
        .about-hero-bg { position: absolute; inset: 0; z-index: 0; }
        .about-hero-bg img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
        .about-hero-overlay { position: absolute; inset: 0; background: rgba(6, 78, 59, 0.6); }
        .about-hero-content { position: relative; z-index: 10; width: 100%; padding: 6rem 0 3rem; text-align: center; }
        .about-hero-content h1 { font-size: 2rem; font-weight: 700; color: #fff; margin-bottom: 1rem; }
        @media (min-width: 768px) { .about-hero-content h1 { font-size: 3rem; } }
        .about-hero-content p { font-size: 1rem; color: rgba(255,255,255,0.8); max-width: 36rem; margin: 0 auto; line-height: 1.6; }
        @media (min-width: 768px) { .about-hero-content p { font-size: 1.125rem; } }

        /* ---------- СЕКЦИИ ---------- */
        .section { padding: 3.5rem 0; }
        @media (min-width: 768px) { .section { padding: 5rem 0; } }
        .section-alt { background: rgba(240, 253, 244, 0.3); }

        .section-header { text-align: center; margin-bottom: 2.5rem; }
        @media (min-width: 768px) { .section-header { margin-bottom: 3.5rem; } }
        .section-header h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem; }
        @media (min-width: 768px) { .section-header h2 { font-size: 2rem; } }
        @media (min-width: 1024px) { .section-header h2 { font-size: 2.5rem; } }
        .section-header p { color: #6b7280; font-size: 0.875rem; max-width: 32rem; margin: 0 auto; }
        @media (min-width: 768px) { .section-header p { font-size: 1rem; } }

        .icon-box { width: 3rem; height: 3rem; border-radius: 0.75rem; background: var(--forest-50); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--forest-600); margin: 0 auto 1.25rem; }
        @media (min-width: 768px) { .icon-box { width: 3.5rem; height: 3.5rem; font-size: 1.5rem; } }

        /* ---------- МИССИЯ ---------- */
        .mission-text { font-size: 1rem; color: #4b5563; line-height: 1.7; text-align: center; max-width: 48rem; margin: 0 auto; }
        @media (min-width: 768px) { .mission-text { font-size: 1.125rem; } }

        /* ---------- КАК РАБОТАЕТ ---------- */
        .steps-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
        @media (min-width: 640px) { .steps-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .steps-grid { grid-template-columns: repeat(4, 1fr); } }
        .step-card { background: #fff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #f3f4f6; }
        .step-card .step-number { font-size: 2rem; font-weight: 700; color: var(--forest-200); margin-bottom: 0.75rem; }
        @media (min-width: 768px) { .step-card .step-number { font-size: 2.5rem; } }
        .step-card h3 { font-weight: 600; font-size: 0.9375rem; color: #111827; margin-bottom: 0.5rem; }
        @media (min-width: 768px) { .step-card h3 { font-size: 1rem; } }
        .step-card p { font-size: 0.8125rem; color: #6b7280; line-height: 1.5; }
        @media (min-width: 768px) { .step-card p { font-size: 0.875rem; } }

        /* ---------- ЦЕННОСТИ ---------- */
        .values-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 640px) { .values-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .values-grid { grid-template-columns: repeat(4, 1fr); } }
        .value-card { background: #fff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #f3f4f6; transition: border-color 0.2s; }
        .value-card:hover { border-color: var(--forest-200); }
        .value-card .value-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; background: var(--forest-50); display: flex; align-items: center; justify-content: center; font-size: 1.125rem; color: var(--forest-600); margin-bottom: 1rem; }
        @media (min-width: 768px) { .value-card .value-icon { width: 3rem; height: 3rem; font-size: 1.25rem; } }
        .value-card h3 { font-weight: 600; font-size: 0.9375rem; color: #111827; margin-bottom: 0.5rem; }
        @media (min-width: 768px) { .value-card h3 { font-size: 1rem; } }
        .value-card p { font-size: 0.8125rem; color: #6b7280; line-height: 1.5; }
        @media (min-width: 768px) { .value-card p { font-size: 0.875rem; } }

        /* ---------- CTA ---------- */
        .cta-box { background: var(--forest-800); border-radius: 1.5rem; padding: 3rem 2rem; text-align: center; }
        @media (min-width: 768px) { .cta-box { padding: 4rem 3rem; } }
        .cta-box h2 { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 1rem; }
        @media (min-width: 768px) { .cta-box h2 { font-size: 2rem; } }
        .cta-box p { font-size: 0.875rem; color: var(--forest-200); max-width: 28rem; margin: 0 auto 1.75rem; }
        @media (min-width: 768px) { .cta-box p { font-size: 1rem; } }
        .cta-buttons { display: flex; flex-direction: column; gap: 0.75rem; justify-content: center; }
        @media (min-width: 640px) { .cta-buttons { flex-direction: row; } }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 500; padding: 0.875rem 2rem; border-radius: 9999px; transition: all 0.2s; cursor: pointer; white-space: nowrap; font-size: 0.9375rem; }
        .btn-primary { background: var(--forest-500); color: #fff; border: none; }
        .btn-primary:hover { background: var(--forest-400); }
        .btn-outline { background: rgba(255,255,255,0.1); color: #fff; border: 1.5px solid rgba(255,255,255,0.4); backdrop-filter: blur(8px); }
        .btn-outline:hover { background: rgba(255,255,255,0.2); }

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
    </style>
</head>
<body>

<!-- ==================== ШАПКА ==================== -->
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
<!-- ==================== HERO ==================== -->
<section class="about-hero">
    <div class="about-hero-bg">
        <img src="img/back.webp">
        <div class="about-hero-overlay"></div>
    </div>
    <div class="about-hero-content">
        <div class="container">
            <h1 class="font-display">Меняем город вместе</h1>
            <p>ЭкоГород — это платформа, которая объединяет жителей для создания чистой, зеленой и устойчивой городской среды</p>
        </div>
    </div>
</section>

<!-- ==================== МИССИЯ ==================== -->
<section class="section">
    <div class="container">
        <div class="icon-box">
            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v8"/><path d="M8 6c0 4 4 8 4 8s4-4 4-8"/><path d="M4 22h16"/></svg>
        </div>
        <h2 class="font-display" style="text-align:center;font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:1rem;">Наша миссия</h2>
        <p class="mission-text">Мы верим, что экологические изменения начинаются с простых шагов. Наша цель — сделать участие в экологических инициативах доступным, понятным и увлекательным для каждого горожанина. От первого субботника до масштабного экофестиваля — мы создаем инфраструктуру, где каждый может найти свое место.</p>
    </div>
</section>

<!-- ==================== КАК ЭТО РАБОТАЕТ ==================== -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <h2 class="font-display">Как это работает</h2>
            <p>Всего четыре шага от знакомства с платформой до реального экологического вклада</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">01</div>
                <h3>Найди мероприятие</h3>
                <p>Просматривай афишу, фильтруй по категориям и интересам. Для каждого найдется свое занятие.</p>
            </div>
            <div class="step-card">
                <div class="step-number">02</div>
                <h3>Запишись в один клик</h3>
                <p>Создай аккаунт и нажми «Записаться». Никаких сложных форм и ожиданий.</p>
            </div>
            <div class="step-card">
                <div class="step-number">03</div>
                <h3>Приходи и участвуй</h3>
                <p>Получи напоминание, приди на мероприятие, встреть единомышленников и сделай реальный вклад.</p>
            </div>
            <div class="step-card">
                <div class="step-number">04</div>
                <h3>Отслеживай свой путь</h3>
                <p>В личном кабинете видно все твои мероприятия: прошедшие и будущие. Следи за своим экологическим следом.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== ЦЕННОСТИ ==================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="font-display">Наши ценности</h2>
            <p>Принципы, которые определяют каждое наше решение и действие</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h3>Сообщество</h3>
                <p>Верим в силу коллектива. Каждое мероприятие — это возможность познакомиться с единомышленниками и создать настоящее экологическое сообщество.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                </div>
                <h3>Просвещение</h3>
                <p>Образование — основа изменений. Проводим лекции, семинары и мастер-классы, чтобы каждый мог делать осознанный выбор в пользу природы.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                </div>
                <h3>Устойчивость</h3>
                <p>Стремимся к долгосрочному результату. Каждое дерево, каждый собранный килограмм вторсырья — это вклад в будущее нашего города.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <h3>Прозрачность</h3>
                <p>Открыто делимся результатами. Все мероприятия, их участники и достижения видны всем — так мы строим доверие.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA ==================== -->
<section class="section section-alt">
    <div class="container">
        <div class="cta-box">
            <h2 class="font-display">Готов сделать первый шаг?</h2>
            <p>Присоединяйся к тысячам горожан, которые уже меняют свой город к лучшему</p>
            <div class="cta-buttons">
                <a href="/events" class="btn btn-primary">
                    <span>Смотреть мероприятия</span>
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="/register" class="btn btn-outline"><span>Создать аккаунт</span></a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col footer-col--brand">
                    <a href="/" class="footer-logo">
                        <div class="footer-logo-icon">
                            <img src="../img/logo.png" alt="">
                        </div>
                        <span class="footer-logo-text font-display">ЭкоГород</span>
                    </a>
                    <p class="footer-description">Платформа для объединения горожан вокруг экологических инициатив. Вместе делаем город чище и зеленее.</p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Платформа</h4>
                    <ul class="footer-links">
                        <li><a href="/">Главная</a></li>
                        <li><a href="/events">Мероприятия</a></li>
                        <li><a href="/about">О проекте</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Участникам</h4>
                    <ul class="footer-links">
                        <li><a href="/about">Как записаться</a></li>
                        <li><a href="/about">Правила участия</a></li>
                        <li><a href="/login">Личный кабинет</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Организаторам</h4>
                    <ul class="footer-links">
                        <li><a href="/admin">Создать мероприятие</a></li>
                        <li><a href="/about">Партнерство</a></li>
                    </ul>
                </div>
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
                    <a href="#" class="footer-social-link" aria-label="VK"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.68 13.74c1.08 1.07 2.22 2.07 2.85 3.44.19.41.19.41-.23.41h-2.08c-.52 0-.72-.22-1.04-.64-.55-.7-1.19-1.44-1.79-1.9-.35-.3-.51-.22-.51.22v1.36c0 .57-.19.73-.64.73-1.22.07-2.48-.08-3.6-.95-1.29-1.02-2.19-2.57-3.05-4.11C4.9 10.78 4.24 9.29 3.6 7.78c-.14-.35-.03-.52.38-.52h2.08c.34 0 .5.16.64.44.68 1.44 1.56 2.76 2.46 3.97.23.31.44.44.6.12.16-.32.08-1.13.05-1.75-.03-.66-.23-1.02-.64-1.18-.2-.08-.12-.33.08-.49.36-.26.94-.29 1.57-.27.79.02 1.03.22 1.13.75.13.68.1 1.58.22 2.25.07.38.3.47.5.23.52-.61.97-1.29 1.38-2.01.16-.28.27-.44.68-.44h2.17c.46 0 .58.21.47.52-.22.66-1.22 1.85-1.92 2.73-.42.52-.44.73-.03 1.13z"/></svg></a>
                    <a href="#" class="footer-social-link" aria-label="Telegram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.66-.52.36-1 .53-1.42.52-.47-.01-1.37-.26-2.03-.48-.82-.27-1.47-.42-1.41-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.63-2.87 7.95-3.42 3.78-1.57 4.57-1.84 5.08-1.85.11 0 .37.03.54.17.14.12.18.28.2.45.02.17.01.34.01.34z"/></svg></a>
                    <a href="#" class="footer-social-link" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg></a>
                    <a href="#" class="footer-social-link" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
                <a href="/about.html" class="footer-privacy">Политика конфиденциальности</a>
            </div>
        </div>
    </div>
</footer>

<script>
// Скролл-эффект для шапки
const header = document.getElementById('header');
window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
}, { passive: true });
</script>
</body>
</html>