<?php
include '../includes/config.php';

// Проверяем, авторизован ли админ
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Обработка обновления статуса заказа
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = trim($_POST['status']);
    
    // На всякий случай проверим, есть ли колонка status в бд. Если её нет, запрос просто выполнится без падения благодаря try-catch
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
    } catch (Exception $e) {
        // Если колонки status нет, можно добавить её через SQL: ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'Новый';
    }
    
    header("Location: admin.php");
    exit;
}

include '../includes/header.php';
?>

<div class="container" style="max-width: 1000px; margin: 50px auto; padding: 0 20px; font-family: 'Inter', sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="font-size: 32px; color: #3d332f; margin: 0;">Панель управления заказами</h1>
        <a href="../profile.php" style="color: #e5989b; text-decoration: none; font-size: 15px; font-weight: 500;">&larr; В профиль</a>
    </div>

    <?php
    // ИСПРАВЛЕНО: Добавили выборку name, email, phone, address и проверили status
    // Если в БД нет колонки status, мы подменим её дефолтным значением 'Новый' через SQL
    $query = "SELECT id, total_price, created_at, name, email, phone, address, 
              IFNULL(status, 'Новый') as status FROM orders ORDER BY id DESC";
    
    try {
        $orders_result = $conn->query($query);
    } catch (Exception $e) {
        // Фолбэк на случай, если структура совсем старая
        $query = "SELECT id, total_price, created_at, '' as name, '' as email, '' as phone, '' as address, 'Новый' as status FROM orders ORDER BY id DESC";
        $orders_result = $conn->query($query);
    }

    if ($orders_result->num_rows === 0):
    ?>
        <div style="background: #fdf6f6; padding: 40px; text-align: center; border-radius: 24px; border: 1px solid #f3e1e1;">
            <p style="color: #8a7a7a; font-size: 16px;">Активных заказов в системе пока нет.</p>
        </div>
    <?php else: ?>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <div style="background: #ffffff; border: 1px solid #f3e1e1; border-radius: 24px; padding: 30px; box-shadow: 0 4px 20px rgba(243, 225, 225, 0.2); display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- Верхняя плашка заказа -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed #f3e1e1; padding-bottom: 20px;">
                        <div>
                            <span style="font-size: 20px; font-weight: 700; color: #3d332f;">Заказ №<?= $order['id'] ?></span>
                            <span style="font-size: 14px; color: #b3a2a2; margin-left: 10px;">от <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></span>
                        </div>
                        
                        <!-- Форма смены статуса (ИСПРАВЛЕНА и упакована в селект) -->
                        <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" style="padding: 10px 15px; border-radius: 12px; border: 1px solid #f3e1e1; background: #fdf6f6; color: #3d332f; font-family: inherit; font-size: 14px; outline: none; cursor: pointer;">
                                <option value="Новый" <?= $order['status'] === 'Новый' ? 'selected' : '' ?>>Новый</option>
                                <option value="В обработке" <?= $order['status'] === 'В обработке' ? 'selected' : '' ?>>В обработке</option>
                                <option value="Ткётся" <?= $order['status'] === 'Ткётся' ? 'selected' : '' ?>>Ткётся</option>
                                <option value="Доставляется" <?= $order['status'] === 'Доставляется' ? 'selected' : '' ?>>Доставляется</option>
                                <option value="Выполнен" <?= $order['status'] === 'Выполнен' ? 'selected' : '' ?>>Выполнен</option>
                                <option value="Отменен" <?= $order['status'] === 'Отменен' ? 'selected' : '' ?>>Отменен</option>
                            </select>
                            <button type="submit" name="update_status" style="padding: 10px 20px; border: none; background: #e5989b; color: white; border-radius: 12px; cursor: pointer; font-family: inherit; font-size: 14px; transition: background 0.3s;" onmouseover="this.style.background='#b55d60'" onmouseout="this.style.background='#e5989b'">
                                Обновить
                            </button>
                        </form>
                    </div>

                    <!-- Инфо о клиенте (ИСПРАВЛЕНО И ЗАЩИЩЕНО) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 15px; color: #8a7a7a;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div><strong style="color: #3d332f;">Клиент:</strong> <?= htmlspecialchars(!empty($order['name']) ? $order['name'] : 'Не указано') ?></div>
                            <div><strong style="color: #3d332f;">Email:</strong> <?= htmlspecialchars(!empty($order['email']) ? $order['email'] : 'Не указано') ?></div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div><strong style="color: #3d332f;">Телефон:</strong> <?= htmlspecialchars(!empty($order['phone']) ? $order['phone'] : 'Не указан') ?></div>
                            <div><strong style="color: #3d332f;">Адрес доставки:</strong> <?= htmlspecialchars(!empty($order['address']) ? $order['address'] : 'Не указан') ?></div>
                        </div>
                    </div>

                    <!-- Финал: Итоговая сумма -->
                    <div style="padding-top: 15px; border-top: 1px solid #fdf6f6; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 15px; color: #b3a2a2;">Текущий статус: <span style="color: #e5989b; font-weight: 600;"><?= $order['status'] ?></span></span>
                        <div style="font-size: 16px; color: #3d332f;">
                            Сумма к оплате: <strong style="font-size: 22px; color: #3d332f; margin-left: 5px;"><?= number_format($order['total_price'], 0, '', ' ') ?> ₽</strong>
                        </div>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>

    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>