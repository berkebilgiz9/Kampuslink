<?php
session_start();
require_once '../config/db.php';

// Admin kontrolü
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    $_SESSION['error'] = "Bu işlemi gerçekleştirmek için admin olmalısınız.";
    header("Location: ../public/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    //Bağlantılı verileri de silme
    $conn->beginTransaction();

    try {
        // Örnek: user_club_memberships tablosundaki kayıtları sil
        $conn->prepare("DELETE FROM user_club_memberships WHERE user_id = :user_id")
             ->execute([':user_id' => $userId]);

        // Kullanıcıyı sil
        $conn->prepare("DELETE FROM users WHERE id = :user_id")
             ->execute([':user_id' => $userId]);

        $conn->commit();
        $_SESSION['success'] = "Kullanıcı başarıyla silindi.";
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Kullanıcı silinirken bir hata oluştu: " . $e->getMessage();
    }
}

header("Location: ../admin/approve_users.php");
exit;
