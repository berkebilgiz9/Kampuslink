<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "Tüm alanları doldurunuz.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Yeni şifreler uyuşmuyor.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current_password, $user['password'])) {
            $errors[] = "Mevcut şifreniz hatalı.";
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashed, $user_id]);
            $success = "Şifreniz başarıyla değiştirildi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Değiştir</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="profile-container">
    <h2 style="color: #ffd700;">🔑 Şifre Değiştir</h2>

    <?php foreach ($errors as $error): ?>
        <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <p style="color: green"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Mevcut Şifre:</label><br>
        <input type="password" name="current_password" required><br><br>

        <label>Yeni Şifre:</label><br>
        <input type="password" name="new_password" required><br><br>

        <label>Yeni Şifre (Tekrar):</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit" class="logout-button">Şifreyi Güncelle</button>
    </form>
</div>
</body>
</html>
