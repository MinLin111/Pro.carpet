<?php
include 'includes/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit;
}

if (isset($_GET['remove_one'])) {
    $id = (int)$_GET['remove_one'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container">
    <h1 class="cart-page-title">Ваш выбор</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="cart-empty-state">
            <p>В корзине пока ничего нет. Загляните в наши коллекции, чтобы найти идеальный ковер.</p>
            <a href="catalog.php" class="btn">Перейти в каталог</a>
        </div>
    <?php else: ?>

        <div class="cart-wrapper">
            
            <div class="cart-items-table">
                <div class="table-header">
                    <div class="col-product">Наименование</div>
                    <div class="col-qty">Количество</div>
                    <div class="col-price">Стоимость</div>
                    <div class="col-actions"></div>
                </div>

                <?php
                $total = 0;
                $conn->set_charset("utf8mb4"); 

                foreach ($_SESSION['cart'] as $id => $qty) {
                    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $product = $stmt->get_result()->fetch_assoc();

                    if (!$product) continue;

                    $sum = $product['price'] * $qty;
                    $total += $sum;
                ?>
                
                <div class="table-row">
                    <div class="col-product border-cell">
                        <div class="cart-product-info">
                            <img src="images/<?= $product['image'] ?>" alt="<?= $product['title'] ?>">
                            <div>
                                <h3><?= $product['title'] ?></h3>
                                <small><?= number_format($product['price'], 0, '', ' ') ?> ₽ / шт.</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-qty border-cell">
                        <div class="quantity-controls">
                            <a href="cart.php?remove_one=<?= $id ?>" class="qty-btn">—</a>
                            <span class="qty-num"><?= $qty ?></span>
                            <a href="cart.php?add=<?= $id ?>" class="qty-btn">+</a>
                        </div>
                    </div>

                    <div class="col-price border-cell">
                        <strong><?= number_format($sum, 0, '', ' ') ?> ₽</strong>
                    </div>

                    <div class="col-actions border-cell">
                        <a href="cart.php?delete=<?= $id ?>" class="delete-link" title="Удалить">✕</a>
                    </div>
                </div>

                <?php } ?>

                <div class="table-footer-actions">
                    <a href="cart.php?clear" class="clear-cart-link">Очистить корзину</a>
                </div>
            </div>

            <div class="cart-summary-card">
                <h3>Детали заказа</h3>
                <div class="summary-line">
                    <span>Товары</span>
                    <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
                </div>
                <div class="summary-line">
                    <span>Доставка</span>
                    <span class="free-shipping">Бесплатно</span>
                </div>
                <div class="summary-total-border"></div>
                <div class="summary-line total">
                    <span>Итого:</span>
                    <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
    <a href="checkout.php" class="btn">Перейти к оформлению</a>
<?php else: ?>
    <a href="login.php?redirect=checkout" class="btn">Войти и оформить заказ</a>
<?php endif; ?>
            </div>

        </div>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>