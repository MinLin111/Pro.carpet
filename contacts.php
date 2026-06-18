<?php
include 'includes/config.php';
include 'includes/header.php';
?>

<div class="container">
    <div class="contacts-center-wrapper">
        
        <h1 class="contacts-title">Связаться с нами</h1>
        <p class="contacts-subtitle">Обсудите ваш будущий проект за чашкой кофе в нашем шоуруме или пригласите дизайнера на дом для точного замера и подбора образцов материалов.</p>
        
        <div class="contacts-grid">
            <div class="contact-item">
                <span>Телефон</span>
                <a href="tel:+79991234567">+7 (999) 123-45-67</a>
            </div>
            <div class="contact-item">
                <span>Электронная почта</span>
                <a href="mailto:info@carpetlux.ru">info@carpetlux.ru</a>
            </div>
            <div class="contact-item">
                <span>Адрес шоурума</span>
                <p>Москва, ул. Дизайнерская, 15</p>
                <small>Ежедневно с 10:00 до 21:00</small>
            </div>
        </div>

        <form action="#" method="POST" class="contacts-form">
            <h3>Начать проект</h3>
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="tel" name="phone" placeholder="Номер телефона" required>
            <textarea name="message" rows="4" placeholder="Расскажите о ваших пожеланиях (размер ковра, цвета, стиль)..."></textarea>
            <button type="submit" class="btn">отправить запрос</button>
        </form>

        <div class="contacts-image-block">
            <img src="images/contacts.jpg" alt="Шоурум Carpet Lux">
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>