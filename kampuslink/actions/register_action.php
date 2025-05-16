<?php
session_start();  
require_once '../config/db.php';

$fname = trim($_POST['first_name']);
$lname = trim($_POST['last_name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$passwordConfirm = $_POST['password_confirm'];
$role_name = $_POST['role'];

if (!preg_match("/^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]+$/u", $fname)) { 
    $_SESSION['error'] = "Ad kısmı sadece harflerden oluşmalıdır!";
    header("Location: ../public/register.php");
    exit;
}
if (!preg_match("/^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]+$/u", $lname)) {
    $_SESSION['error'] = "Soyad kısmı sadece harflerden oluşmalıdır!";
    header("Location: ../public/register.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  //eposta doğrulama
    $_SESSION['error'] = "Geçerli bir e-posta adresi giriniz!";
    header("Location: ../public/register.php");
    exit;
}

if ($password !== $passwordConfirm) {
    $_SESSION['error'] = "Girilen şifreler birbiriyle uyuşmuyor!";
    header("Location: ../public/register.php");
    exit;
}

$hPassword = password_hash($password, PASSWORD_BCRYPT); //kriptolama

$expr = $conn->prepare("SELECT * FROM users WHERE email = :email");
$expr->bindParam(':email', $email);
$expr->execute();

if ($expr->rowCount() > 0) {
    $_SESSION['error'] = "Bu e-posta zaten kayıtlıdır!";
    header("Location: ../public/register.php"); 
    exit;
}

$isApproved = ($role_name === 'öğrenci') ? 1 : 0;
$insert = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (:fname, :lname, :email, :password, :role_name)");
$insert->bindParam(':fname', $fname);
$insert->bindParam(':lname', $lname);
$insert->bindParam(':email', $email);
$insert->bindParam(':password', $hPassword);
$insert->bindParam(':role_name', $role_name);
$insert->execute();

if ($role_name === 'öğrenci') {
    $role_stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = :role_name LIMIT 1");
    $role_stmt->execute([':role_name' => $role_name]);
    $role_id = $role_stmt->fetchColumn();

    if (!$role_id) {
        $_SESSION['error'] = "Geçersiz rol seçimi!";
        header("Location: ../public/register.php");
        exit;
    }

    $membership_stmt = $conn->prepare("INSERT INTO user_club_memberships (user_id, role_id) VALUES (:user_id, :role_id)");
    $membership_stmt->execute([
        ':user_id' => $user_id,
        ':role_id' => $role_id
    ]);
}


$_SESSION['success'] = "Kayıt başarılı! " . 
    ($role_name === 'görevli' ? "Admin onayını bekleyiniz." : "Giriş yapabilirsiniz.");
header("Location: ../public/login.php");
exit;
?>
