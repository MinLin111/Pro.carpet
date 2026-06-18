<?php
include 'includes/config.php';

// Защита: если пользователь не вошел, отправляем на вход
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

include 'includes/header.php';
?>

<div class="container" style="max-width: 800px; margin: 50px auto; padding: 0 20px;">
    <h1 style="font-size: 36px; margin-bottom: 30px; color: #3d332f;">Мои заказы</h1>

    <?php
    // Получаем все заказы пользователя, сортируя их: новые вверху
    $stmt = $conn->prepare("SELECT id, total_price, created_at, address FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders_result = $stmt->get_result();

    if ($orders_result->num_rows === 0): 
    ?>
        <div style="background: rgba(245, 238, 230, 0.4); padding: 40px; text-align: center; border-radius: 24px; border: 1px solid #d9cfc5;">
            <p style="font-size: 18px; color: #7a6e69; margin-bottom: 20px;">Вы еще не оформили ни одного заказа.</p>
            <a href="catalog.php" class="btn">Перейти в каталог</a>
        </div>
    <?php else: ?>

        <div class="orders-list" style="display: flex; flex-direction: column; gap: 25px;">
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <div class="order-card" style="background: rgba(245, 238, 230, 0.3); border: 1px solid #d9cfc5; padding: 30px; border-radius: 20px;">
                    
                    <div class="order-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(217, 207, 197, 0.6); padding-bottom: 15px; margin-bottom: 15px;">
                        <div>
                            <h3 style="font-size: 20px; color: #3d332f; margin: 0;">Заказ №<?= $order['id'] ?></h3>
                            <span style="font-size: 14px; color: #a3958f;">от <?= date('d.m.Y в H:i', strtotime($order['created_at'])) ?></span>
                        </div>
                        <div style="background: #e3ece6; color: #2e6243; padding: 6px 16px; border-radius: 999px; font-size: 13px; font-weight: 500;">
                            Принят / Новый
                        </div>
                    </div>

                    <div class="order-details" style="font-size: 15px; color: #5e5551; display: flex; flex-direction: column; gap: 8px;">
                        <div>
                            <strong>Адрес доставки:</strong> 
                            <?= htmlspecialchars(isset($order['address']) ? $order['address'] : 'Не указан') ?>
                        </div>
                        
                        <div style="margin-top: 10px; padding-top: 15px; border-top: 1px dashed rgba(217, 207, 197, 0.6); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 16px;">Сумма заказа:</span>
                            <strong style="font-size: 22px; color: #3d332f;"><?= number_format($order['total_price'], 0, '', ' ') ?> ₽</strong>
                        </div>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>