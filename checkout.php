<?php
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: catalog.php");
    exit;
}

$error = '';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email = '';

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res) {
        $email = $res['email'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_name = trim($_POST['name']);
    $c_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $c_phone = trim($_POST['phone']);
    $c_address = trim($_POST['address']);

    // СЕРВЕРНАЯ ВАЛИДАЦИЯ
    if (!filter_var($c_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный формат email.';
    } elseif (!preg_match('/^[0-9+\s\-\(\)]{7,20}$/', $c_phone)) {
        $error = 'Введите корректный номер телефона (только цифры и символы).';
    } elseif (empty($c_name) || empty($c_address)) {
        $error = 'Все поля обязательны для заполнения.';
    } else {
        $user_id = $_SESSION['user_id'];
        $default_status = 'Новый';
        $total_price = 0;
        $items = [];

        foreach ($_SESSION['cart'] as $id => $qty) {
            $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();
            if ($product) {
                $total_price += $product['price'] * $qty;
                $items[] = ['id' => $id, 'qty' => $qty, 'price' => $product['price']];
            }
        }

        if ($total_price > 0) {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $user_id, $c_name, $c_email, $c_phone, $c_address, $total_price, $default_status);
            
            if ($stmt->execute()) {
                $order_id = $conn->insert_id;
                foreach ($items as $item) {
                    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['qty'], $item['price']);
                    $item_stmt->execute();
                }
                unset($_SESSION['cart']);
                header("Location: orders.php");
                exit;
            } else {
                $error = 'Ошибка при сохранении заказа.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <h1 class="checkout-page-title">Оформление заказа</h1>

    <?php if (!empty($error)): ?>
        <div class="auth-error-message" style="color: red; margin-bottom: 20px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="checkout-wrapper">
        <div class="checkout-form-section">
            <h2>Данные доставки</h2>
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Ваше имя</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Константин" required>
                </div>
                <div class="form-group">
                    <label for="email">Электронная почта</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Номер телефона</label>
                    <input type="tel" id="phone" name="phone" placeholder="+7 (999) 000-00-00" 
                           pattern="^[0-9+\s\-\(\)]{7,20}$" 
                           title="Только цифры и символы телефона" required>
                </div>
                <div class="form-group">
                    <label for="address">Адрес доставки</label>
                    <input type="text" id="address" name="address" placeholder="г. Москва, ул. Пречистенка, д. 10" required>
                </div>
                <button type="submit" class="btn checkout-submit-btn">Подтвердить заказ</button>
            </form>
        </div>
        </div>
</div>

<?php include 'includes/footer.php'; ?>