<?php
session_start();
require_once '../config/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  //eposta doğrulama
    $_SESSION['error'] = "Geçerli bir e-posta adresi giriniz!";
    header("Location: ../public/login.php");
    exit;
}

$expr = $conn->prepare("SELECT * FROM users WHERE email = :email");
$expr->bindParam(':email', $email);
$expr->execute();
$user = $expr->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {  //eposta şifre kontrol   
    if (!$user['is_approved']) {
        $_SESSION['error'] = "Hesabınız henüz onaylanmamış. Lütfen admin onayını bekleyin.";
        header("Location: ../public/login.php");
        exit;
    }
    
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'is_admin' => $user['is_admin']
    ];

    header("Location: ../public/index.php");
    exit;
} else {
    $_SESSION['error'] = "E-posta veya şifre geçersiz!";
    header("Location: ../public/login.php");
}
