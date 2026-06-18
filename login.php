<?php
include 'includes/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Определяем, куда перенаправлять пользователя после авторизации
    $redirect_target = (isset($_GET['redirect']) && $_GET['redirect'] === 'checkout') ? 'checkout.php' : 'profile.php';

    // 1. Проверяем, не пытается ли войти главный админ напрямую
    if ($email === 'pro.carpet' && $password === 'pro.carpet123') {
        $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
        } else {
            $_SESSION['user_id'] = 999; 
            $_SESSION['name'] = 'Администратор';
        }

        $_SESSION['admin_logged'] = true;
        
        header("Location: " . $redirect_target);
        exit;
    }

    // 2. Логика для обычных пользователей
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            
            if ($user['email'] === 'pro.carpet') {
                $_SESSION['admin_logged'] = true;
            }

            header("Location: " . $redirect_target);
            exit;
        } else {
            $error = 'Неверный пароль.';
        }
    } else {
        $error = 'Пользователь с таким Email не найден.';
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="auth-wrapper" style="max-width: 450px; margin: 60px auto; padding: 0 20px;">
        <h1 class="auth-title" style="font-size: 36px; text-align: center; margin-bottom: 10px;">Добро пожаловать</h1>
        <p class="auth-subtitle" style="text-align: center; color: #7a6e69; margin-bottom: 40px; font-size: 16px;">
            Войдите в личный кабинет бутика Carpet Lux
        </p>

        <div class="auth-card" style="background: rgba(245, 238, 230, 0.5); border: 1px solid #d9cfc5; padding: 40px; border-radius: 24px;">
            <h2 style="font-size: 24px; margin-bottom: 25px; color: #3d332f;">Вход</h2>

            <?php if (!empty($error)): ?>
                <div class="auth-error-message" style="background: rgba(216, 185, 180, 0.2); border: 1px solid #d8b9b4; color: #3d332f; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 15px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form" style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="email" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #a3958f;">Электронная почта / Логин</label>
                    <input type="text" id="email" name="email" placeholder="example@mail.ru или pro.carpet" required style="padding: 14px 20px; border-radius: 12px; border: 1px solid #d9cfc5; background: #fdfbf7; font-family: inherit; font-size: 16px; color: #3d332f;">
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="password" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #a3958f;">Пароль</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required style="padding: 14px 20px; border-radius: 12px; border: 1px solid #d9cfc5; background: #fdfbf7; font-family: inherit; font-size: 16px; color: #3d332f;">
                </div>

                <button type="submit" class="btn auth-btn" style="width: 100%; padding: 16px; font-size: 16px; cursor: pointer; border: none; background: #3d332f; color: #efe8df; border-radius: 999px; text-transform: lowercase;">
                    Войти
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 25px; text-align: center; font-size: 15px; color: #7a6e69;">
                <p>Нет аккаунта? <a href="register.php" style="color: #3d332f; text-decoration: underline;">Зарегистрироваться</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 