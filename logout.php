<?php
// logout.php - Выход из системы

session_start();

// Уничтожаем все данные сессии
session_destroy();

// Перенаправляем на страницу входа
header("Location: login.php");
exit;
?>