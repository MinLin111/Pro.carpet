<?php
include 'includes/config.php';

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: catalog.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container">
    <div class="product-single-wrapper">
        
        <div class="product-single-image">
            <img src="images/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>">
        </div>

        <div class="product-single-info">
            <div class="product-meta">Коллекция Carpet Lux</div>
            <h1 class="product-single-title"><?= htmlspecialchars($product['title']) ?></h1>
            
            <div class="product-single-price">
                <?= number_format($product['price'], 0, '', ' ') ?> ₽
            </div>
            
            <div class="product-single-divider"></div>
            
            <div class="product-single-desc">
                <h3>О продукте</h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
            </div>
            
            <div class="product-single-features">
                <div class="feature-item">✦ 100% натуральные материалы</div>
                <div class="feature-item">✦ Ручная работа / Высокая плотность</div>
            </div>

            <a class="btn product-single-btn" href="cart.php?add=<?= $product['id'] ?>">
                Добавить в корзину
            </a>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>