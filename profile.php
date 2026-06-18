<?php
include 'includes/config.php';
include 'includes/auth.php';

checkAuth();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

include 'includes/header.php';
?>

<div class="container">
    <div class="profile-wrapper">
        
        <div class="profile-header">
            <h1>Здравствуйте, <?= htmlspecialchars($user['name']) ?></h1>
            <p class="profile-subtitle">Добро пожаловать в ваше приватное пространство Carpet Lux</p>
        </div>

        <div class="profile-grid">
            
            <div class="profile-card info-card">
                <h2>Личные данные</h2>
                <div class="info-group">
                    <span class="info-label">Имя профиля</span>
                    <span class="info-value"><?= htmlspecialchars($user['name']) ?></span>
                </div>
                <div class="info-group">
                    <span class="info-label">Электронный адрес</span>
                    <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="info-group">
                    <span class="info-label">Дата регистрации</span>
                    <span class="info-value"><?= date('d.m.Y', strtotime($user['created_at'])) ?></span>
                </div>
            </div>

            <div class="profile-card navigation-card">
                <h2>Управление заказами</h2>
                <p>Просматривайте историю ваших покупок, отслеживайте статус доставки и готовность ковров индивидуального ткачества.</p>
                <div class="profile-actions">
                    <a class="btn" href="orders.php">Мои заказы</a>
                    
                    <?php if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true): ?>
                        <a class="btn" href="admin/admin.php" style="background: #3d332f; color: #fdfbf7;">Админ панель</a>
                    <?php endif; ?>

                    <a class="btn-secondary" href="logout.php">Выйти из кабинета</a>
                </div>
            </div>

        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>