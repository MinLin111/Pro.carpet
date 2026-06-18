<?php
include '../includes/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($login === 'pro.carpet' && $password === 'pro.carpet123') {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $login);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                } else {
                    $_SESSION['user_id'] = 999; 
                    $_SESSION['name'] = 'Администратор';
                }
            } else {
                $_SESSION['user_id'] = 999; 
                $_SESSION['name'] = 'Администратор';
            }
        } catch (Exception $e) {
            $_SESSION['user_id'] = 999; 
            $_SESSION['name'] = 'Администратор';
        }

        $_SESSION['admin_logged'] = true;
        
        header("Location: admin.php");
        
        echo "<script>window.location.href='admin.php';</script>";
        exit;
    } else {
        $error = 'Неверный логин администратора или пароль.';
    }
}

include '../includes/header.php';
?>

<div class="container">
    <div class="auth-wrapper" style="max-width: 450px; margin: 60px auto; padding: 0 20px;">
        <h1 class="auth-title" style="font-size: 36px; text-align: center; margin-bottom: 10px;">Панель управления</h1>
        <p class="auth-subtitle" style="text-align: center; color: #7a6e69; margin-bottom: 40px; font-size: 16px;">
            Авторизация для сотрудников и администраторов бутика Carpet Lux
        </p>

        <div class="auth-card" style="background: rgba(245, 238, 230, 0.5); border: 1px solid #d9cfc5; padding: 40px; border-radius: 24px;">
            <h2 style="font-size: 24px; margin-bottom: 25px; color: #3d332f;">Вход в систему</h2>

            <?php if (!empty($error)): ?>
                <div class="auth-error-message" style="background: rgba(216, 185, 180, 0.2); border: 1px solid #d8b9b4; color: #3d332f; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 15px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form" style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="login" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #a3958f;">Идентификатор</label>
                    <input type="text" id="login" name="login" placeholder="pro.carpet" value="<?= htmlspecialchars(isset($_POST['login']) ? $_POST['login'] : '') ?>" required style="padding: 14px 20px; border-radius: 12px; border: 1px solid #d9cfc5; background: #fdfbf7; font-family: inherit; font-size: 16px; color: #3d332f;">
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="password" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #a3958f;">Пароль управления</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required style="padding: 14px 20px; border-radius: 12px; border: 1px solid #d9cfc5; background: #fdfbf7; font-family: inherit; font-size: 16px; color: #3d332f;">
                </div>

                <button type="submit" class="btn auth-btn" style="width: 100%; padding: 16px; font-size: 16px; cursor: pointer; border: none; background: #3d332f; color: #efe8df; border-radius: 999px; text-transform: lowercase; transition: opacity 0.3s ease;">
                    Войти в панель
                </button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>