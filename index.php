<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЭкоГород — Экологический городской портал</title>
    <link rel="stylesheet" href="index.css">
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

<!-- ==================== HERO ==================== -->
<section class="hero">
    <div class="hero-bg">
        <img src="img/1.jpg" alt="Зеленый город">
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-content">
        <div class="container">
            <div class="hero-pill">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8.5c0 4.5-6 9.5-6 9.5s-6-5-6-9.5a6 6 0 1 1 12 0Z"/><circle cx="12" cy="8.5" r="2.5"/></svg>
                <span>Более 500 мероприятий в этом году</span>
            </div>
            <h1 class="hero-title font-display">Делай город<br><span class="highlight">зеленее</span> вместе</h1>
            <p class="hero-subtitle">ЭкоГород объединяет людей для создания чистой и устойчивой среды. Найди мероприятие по душе — от субботника до экофестиваля.</p>
            <div class="hero-buttons">
                <a href="/events.php" class="btn btn-primary">
                    <span>Найти мероприятие</span>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="/about" class="btn btn-outline"><span>Узнать о проекте</span></a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== JAVASCRIPT ==================== -->
<script>
// Скролл-эффект для шапки
(function() {
    const header = document.getElementById('header');
    let lastScroll = 0;

    function updateHeader() {
        const scrollY = window.scrollY;
        if (scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        lastScroll = scrollY;
    }

    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader(); // проверка при загрузке
})();

// Анимация появления секций + счётчики (как прежде)
(function() {
    const animatedSections = document.querySelectorAll('.animated-section');
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                if (entry.target.id === 'stats-section') startCountUp();
                sectionObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    animatedSections.forEach(section => sectionObserver.observe(section));

    function startCountUp() {
        document.querySelectorAll('.count-up').forEach((el, index) => {
            const target = parseInt(el.getAttribute('data-target'));
            const suffix = el.getAttribute('data-suffix') || '';
            const duration = 2000, steps = 60;
            const stepDuration = duration / steps;
            const increment = target / steps;
            let current = 0, step = 0;
            setTimeout(() => {
                const interval = setInterval(() => {
                    step++;
                    current = Math.min(Math.round(increment * step), target);
                    el.textContent = current.toLocaleString('ru-RU') + suffix;
                    if (step >= steps) {
                        clearInterval(interval);
                        el.textContent = target.toLocaleString('ru-RU') + suffix;
                    }
                }, stepDuration);
            }, index * 200);
        });
    }

    const statsSection = document.getElementById('stats-section');
    if (statsSection) {
        const rect = statsSection.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            statsSection.classList.add('is-visible');
            startCountUp();
            sectionObserver.unobserve(statsSection);
        }
    }
})();
</script>


<!-- ==================== STATS ==================== -->
<section class="animated-section stats" id="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">
                    <!-- Календарь -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="stat-number"><span class="count-up" data-target="156" data-suffix="">0</span></div>
                <div class="stat-label">Мероприятий проведено</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <!-- Сердце -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <div class="stat-number"><span class="count-up" data-target="8400" data-suffix="+">0</span></div>
                <div class="stat-label">Участников волонтеров</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <!-- Дерево -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 7h20M5 12h14"/></svg>
                </div>
                <div class="stat-number"><span class="count-up" data-target="3200" data-suffix="">0</span></div>
                <div class="stat-label">Деревьев посажено</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <!-- Переработка -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                </div>
                <div class="stat-number"><span class="count-up" data-target="12" data-suffix=" т">0</span></div>
                <div class="stat-label">Вторсырья собрано</div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CATEGORIES ==================== -->
<section class="animated-section categories" id="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="font-display">Выберите <span class="highlight">направление</span></h2>
            <p>От субботников до экофестивалей — найди то, что подходит тебе!</p>
        </div>
        <div class="categories-grid">
            <!-- Субботник -->
            <a href="/events.php?category=Субботник" class="category-card cat-subotnik">
                <div class="category-icon">
                    <!-- Кисть/метла -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.37 2.63 21.37 5.63"/><path d="M15.37 5.63 18.37 8.63"/><path d="M10 16l-6 6"/><path d="M22 10l-8 8"/><path d="M2 16l6 6"/></svg>
                </div>
                <h3>Субботник</h3>
                <p class="line-clamp-2">Совместная уборка парков, скверов и общественных территорий</p>
            </a>
            <!-- Лекция -->
            <a href="/events.php?category=Лекция и семинар" class="category-card cat-lecture">
                <div class="category-icon">
                    <!-- Книга -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h3>Лекция и семинар</h3>
                <p class="line-clamp-2">Образовательные мероприятия по экологии и устойчивому развитию</p>
            </a>
            <!-- Сбор вторсырья -->
            <a href="/events.php?category=Сбор вторсырья" class="category-card cat-collect">
                <div class="category-icon">
                    <!-- Recycle -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                </div>
                <h3>Сбор вторсырья</h3>
                <p class="line-clamp-2">Пункты приема пластика, бумаги, стекла и металла</p>
            </a>
            <!-- Посадка деревьев -->
            <a href="/events.php?category=Посадка деревьев" class="category-card cat-planting">
                <div class="category-icon">
                    <!-- Саженец -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v8"/><path d="M8 6c0 4 4 8 4 8s4-4 4-8"/><path d="M4 22h16"/></svg>
                </div>
                <h3>Посадка деревьев</h3>
                <p class="line-clamp-2">Зеленые субботники по озеленению города</p>
            </a>
            <!-- Экофестиваль -->
            <a href="/events.php?category=Экофестиваль" class="category-card cat-festival">
                <div class="category-icon">
                    <!-- Земля -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <h3>Экофестиваль</h3>
                <p class="line-clamp-2">Массовые экологические праздники и ярмарки</p>
            </a>
        </div>
    </div>
</section>

<?php
require_once 'db.php';

// Получаем ближайшие 4 мероприятия, сортируя по дате
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 4");
$events = $stmt->fetchAll();

function getCategorySlug($category) {
    $map = [
        'Субботник' => 'subotnik',
        'Лекция и семинар' => 'lecture',
        'Сбор вторсырья' => 'collect',
        'Посадка деревьев' => 'planting',
        'Экофестиваль' => 'festival',
    ];
    return $map[$category] ?? 'subotnik';
}
?>

<!-- ==================== EVENTS SECTION ==================== -->
<section class="animated-section events" id="events-section">
    <div class="container">
        <div class="events-header">
            <div>
                <p class="label">Ближайшие события</p>
                <h2 class="font-display">Афиша мероприятий</h2>
            </div>
            <a href="/events" class="events-all-link">
                <span>Все мероприятия</span>
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>

        <div class="events-grid">
            <?php foreach ($events as $event): ?>
                <a href="/events/1.php?id=<?= $event['id'] ?>" class="event-card">
                <div class="event-image">
                    <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                    <span class="event-badge badge-<?= getCategorySlug($event['category']) ?>">
                        <?= htmlspecialchars($event['category']) ?>
                    </span>
                </div>
                <div class="event-body">
                    <h3 class="line-clamp-2"><?= htmlspecialchars($event['title']) ?></h3>
                    <div class="event-meta">
                        <div class="event-meta-item">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span><?= date('d M, H:i', strtotime($event['event_date'])) ?></span>
                        </div>
                        <div class="event-meta-item">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 8.5c0 4.5-6 9.5-6 9.5s-6-5-6-9.5a6 6 0 1 1 12 0Z"/>
                                <circle cx="12" cy="8.5" r="2.5"/>
                            </svg>
                            <span><?= htmlspecialchars($event['location']) ?></span>
                        </div>
                    </div>

                    <?php
                        $current = (int)$event['current_participants'];
                        $max = (int)$event['max_participants'];
                        $percent = $max > 0 ? round(($current / $max) * 100, 2) : 0;
                        $progressClass = 'progress-' . getCategorySlug($event['category']);
                    ?>
                    <div class="event-progress">
                        <div class="event-progress-header">
                            <span class="label">Участников</span>
                            <span class="value"><?= $current ?> / <?= $max ?></span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill <?= $progressClass ?>" style="width: <?= $percent ?>%;"></div>
                        </div>
                    </div>

                    <div class="event-footer">
                        <span class="organizer"><?= htmlspecialchars($event['organizer']) ?></span>
                        <span class="details">Подробнее</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="events-mobile-link">
            <a href="/events">
                <span>Все мероприятия</span>
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>
    </div>
</section>
<!-- ==================== CTA ==================== -->
<section class="animated-section cta" id="cta-section">
    <div class="container">
        <div class="cta-container">
            <div class="cta-bg">
                <img src="https://readdy.ai/api/search-image?query=..." alt="">
                <div class="cta-overlay"></div>
            </div>
            <div class="cta-content">
                <h2 class="font-display">Каждое действие имеет значение</h2>
                <p>Присоединяйся к тысячам горожан, которые уже делают свой вклад в чистый и зеленый город. Твое участие — это первый шаг к переменам.</p>
                <div class="cta-buttons">
                    <a href="/register.php" class="btn btn-cta-primary"><span>Создать аккаунт</span><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                    <a href="/events.php" class="btn btn-cta-outline"><span>Смотреть мероприятия</span></a>
                </div>
            </div>
        </div>
    </div>
</section>

        <!-- ==================== FOOTER ==================== -->
    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    
                    <!-- Логотип и описание -->
                    <div class="footer-col footer-col--brand">
                        <a href="/" class="footer-logo">
                            <div class="footer-logo-icon">
                                <img src="../img/logo.png" alt="">
                            </div>
                            <span class="footer-logo-text font-display">ЭкоГород</span>
                        </a>
                        <p class="footer-description">
                            Платформа для объединения горожан вокруг экологических инициатив. Вместе делаем город чище и зеленее.
                        </p>
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

<!-- ==================== JAVASCRIPT ==================== -->
<script>
(function() {
    const animatedSections = document.querySelectorAll('.animated-section');
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                if (entry.target.id === 'stats-section') startCountUp();
                sectionObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    animatedSections.forEach(section => sectionObserver.observe(section));

    function startCountUp() {
        document.querySelectorAll('.count-up').forEach((el, index) => {
            const target = parseInt(el.getAttribute('data-target'));
            const suffix = el.getAttribute('data-suffix') || '';
            const duration = 2000, steps = 60;
            const stepDuration = duration / steps;
            const increment = target / steps;
            let current = 0, step = 0;
            setTimeout(() => {
                const interval = setInterval(() => {
                    step++;
                    current = Math.min(Math.round(increment * step), target);
                    el.textContent = current.toLocaleString('ru-RU') + suffix;
                    if (step >= steps) {
                        clearInterval(interval);
                        el.textContent = target.toLocaleString('ru-RU') + suffix;
                    }
                }, stepDuration);
            }, index * 200);
        });
    }

    const statsSection = document.getElementById('stats-section');
    if (statsSection) {
        const rect = statsSection.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            statsSection.classList.add('is-visible');
            startCountUp();
            sectionObserver.unobserve(statsSection);
        }
    }
})();
</script>
</body>
</html>