<!DOCTYPE html>
<html lang="ru">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&display=swap" rel="stylesheet">

<title>Carpet Lux</title>

<link rel="stylesheet" href="/assets/css/style.css">

</head>
<body>

<header>

<div class="logo">
    Carpet Lux
</div>

<nav>

<a href="/">Главная</a>
<a href="/about.php">О нас</a>
<a href="/catalog.php">Каталог</a>
<a href="/contacts.php">Контакты</a>

<a href="/cart.php">Корзина</a>

<?php if(isset($_SESSION['user_id'])): ?>

<a href="/orders.php">Заказы</a>
<a href="/profile.php">Профиль</a>
<a href="/logout.php">Выход</a>

<?php else: ?>

<a href="/login.php">Вход</a>
<a href="/register.php">Регистрация</a>

<?php endif; ?>

</nav>

</header>