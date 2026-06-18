    <?php
    include 'includes/config.php';
    include 'includes/header.php';

    $result = $conn->query("SELECT * FROM products");
    ?>

    <div class="container">
        
        <div class="catalog-intro">
            <h1 class="catalog-title">Коллекции Carpet Lux</h1>
            <p class="catalog-subtitle">
                Каждый узор — это зашифрованная история, воплощенная в премиальном шелке и шерсти. 
                Выберите направление, которое дополнит характер вашего интерьера.
            </p>
        </div>

        <div class="patterns-filter">
            <div class="pattern-tab active">
                <div class="pattern-icon geometry"></div>
                <span>Все ковры</span>
            </div>
            <div class="pattern-tab">
                <div class="pattern-icon classic"></div>
                <span>Классические узоры</span>
            </div>
            <div class="pattern-tab">
                <div class="pattern-icon abstract"></div>
                <span>Абстракция</span>
            </div>
            <div class="pattern-tab">
                <div class="pattern-icon minimal"></div>
                <span>Минимализм</span>
            </div>
        </div>

        <div class="products">
            <?php while($product = $result->fetch_assoc()): ?>
            <div class="card">
    <img src="images/<?= $product['image'] ?>">
    
    <div class="card-content">
        <h3><?= $product['title'] ?></h3>
        <p><?= $product['description'] ?></p> <strong><?= number_format($product['price'],0,'',' ') ?> ₽</strong>
        
        <a class="btn" href="product.php?id=<?= $product['id'] ?>">Подробнее</a>
    </div>
</div>
            <?php endwhile; ?>
        </div>

        <div class="catalog-textures-section">
            <h2 class="textures-title">Премиальные текстуры</h2>
            <div class="textures-grid">
                <div class="texture-card">
                    <div class="texture-img-wrapper">
                        <img src="images/texture1.jpg" alt="Новозеландская шерсть">
                    </div>
                    <h3>Новозеландская шерсть</h3>
                    <p>Плотная, мягкая, создающая идеальный рельеф и удерживающая тепло.</p>
                </div>
                <div class="texture-card">
                    <div class="texture-img-wrapper">
                        <img src="images/texture2.jpg" alt="Натуральный шелк">
                    </div>
                    <h3>Натуральный шелк</h3>
                    <p>Благородный перелив ворса, меняющий оттенок в зависимости от падения света.</p>
                </div>
            </div>
        </div>

    </div>

    <?php include 'includes/footer.php'; ?> 