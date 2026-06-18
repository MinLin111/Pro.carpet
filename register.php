<?php
include 'includes/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $existing_user = $check_stmt->get_result()->fetch_assoc();

    if ($existing_user) {
        $error = 'Пользователь с таким email уже зарегистрирован.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = 'Произошла ошибка при регистрации.';
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="auth-wrapper">
        <h1 class="auth-title">Присоединяйтесь к нам</h1>
        <p class="auth-subtitle">Создайте аккаунт, чтобы сохранять эскизы ковров и управлять параметрами заказов.</p>

        <div class="auth-card">
            <h2>Регистрация</h2>

            <?php if (!empty($error)): ?>
                <div class="auth-error-message"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Ваше имя</label>
                    <input type="text" id="name" name="name" placeholder="Александр" required>
                </div>

                <div class="form-group">
                    <label for="email">Электронная почта</label>
                    <input type="email" id="email" name="email" placeholder="example@mail.ru" required>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" placeholder="Придумайте надежный пароль" required>
                </div>

                <button type="submit" class="btn auth-btn">Создать аккаунт</button>
            </form>
            
            <div class="auth-footer">
                <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>