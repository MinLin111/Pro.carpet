<?php
include 'includes/config.php';
include 'includes/header.php';
header('Content-Type: text/html; charset=utf-8');
?>

<section class="hero">
    <img src="images/hero.jpg" class="hero-bg-img" alt="Carpet Lux Premium Background">
    
    <div class="hero-content">
        <h1>
            Ковры на заказ,
            <br>
            чтобы остаться в памяти
        </h1>   

        <p>
            Премиальные материалы • Индивидуальный дизайн
        </p>

        <br>

        <a href="catalog.php" class="btn">
            Смотреть каталог
        </a>
    </div>
</section>

<section>
    <div class="container">
        <div class="about-section">
            <div class="about-text">
                <h2>Философия Carpet Lux</h2>
                <p>
                    Мы создаём ковры не просто как предмет
                    интерьера, а как часть истории дома.
                </p>
                <br>
                <p>
                    Каждый проект разрабатывается индивидуально:
                    размеры, орнамент, материалы и цветовая
                    палитра подбираются под клиента.
                </p>
            </div>
            <div class="about-image">
                <img src="images/about.jpg">
            </div>
        </div>
    </div>
</section>

<!-- ОБНОВЛЕННЫЙ БАННЕР С ПРЯМОЙ ССЫЛКОЙ НА КАРТИНКУ -->
<section class="middle-banner">
    <img src="images/banner.jpg" alt="Carpet Lux Banner">
</section>

<!-- КАТАЛОГ -->
<section>
    <div class="container">
        <h2 class="section-title">
            Популярные коллекции
        </h2>
        <div class="products">
            <?php
            $result = $conn->query(
                "SELECT * FROM products LIMIT 3"
            );
            while($row = $result->fetch_assoc()):
            ?>
            <div class="card">
                <img src="images/<?= $row['image'] ?>">
                <div class="card-content">
                    <h3><?= $row['title'] ?></h3>
                    <p><?= $row['description'] ?></p>
                    <strong>
                        <?= number_format($row['price'],0,'',' ') ?>
                        ₽
                    </strong>
                    <a href="product.php?id=<?= $row['id'] ?>" class="btn">
                        Подробнее
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>