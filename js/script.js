document.addEventListener("DOMContentLoaded", function() {
    // Логика слайдера
    const slides = document.querySelector('.slides');
    if (slides) {
        const slideCount = document.querySelectorAll('.slide').length;
        let currentIndex = 0;

        function updateSlider() {
            slides.style.transform = `translateX(-${currentIndex * 25}%)`;
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slideCount;
            updateSlider();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + slideCount) % slideCount;
            updateSlider();
        }

        // Автопереключение каждые 3 секунды
        let autoSlide = setInterval(nextSlide, 3000);

        const nextBtn = document.querySelector('.next');
        const prevBtn = document.querySelector('.prev');

        if(nextBtn && prevBtn) {
            nextBtn.addEventListener('click', () => {
                clearInterval(autoSlide);
                nextSlide();
                autoSlide = setInterval(nextSlide, 3000);
            });
            prevBtn.addEventListener('click', () => {
                clearInterval(autoSlide);
                prevSlide();
                autoSlide = setInterval(nextSlide, 3000);
            });
        }
    }

    // Интерактивные уведомления / Валидация фронтенда
    const regForm = document.getElementById('regForm');
    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            let valid = true;
            const login = document.getElementById('login').value;
            const pass = document.getElementById('password').value;
            
            // Очистка старых ошибок
            document.querySelectorAll('.error-hint').forEach(el => el.innerText = '');

            if (login.length < 6 || !/^[a-zA-Z0-9]+$/.test(login)) {
                document.getElementById('login-error').innerText = 'Минимум 6 символов (латиница и цифры).';
                valid = false;
            }
            if (pass.length < 8) {
                document.getElementById('pass-error').innerText = 'Пароль должен быть от 8 символов.';
                valid = false;
            }

            if (!valid) e.preventDefault();
        });
    }
});