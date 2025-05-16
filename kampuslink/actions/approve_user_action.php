<?php
session_start();
require_once '../config/db.php';

// Admin kontrol
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    $_SESSION['error'] = "Bu sayfaya erişmek için admin olmalısınız!";
    header("Location: ../public/login.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {

        $stmt = $conn->prepare("SELECT role FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $role = $stmt->fetchColumn();

        if ($role === 'görevli') {
        
            $roleStmt = $conn->prepare("SELECT id FROM roles WHERE role_name = 'görevli' LIMIT 1");
            $roleStmt->execute();
            $role_id = $roleStmt->fetchColumn();

            $club_id = $_POST['club_id'] ?? null;
            
            if ($role_id && $club_id) {
                $addRole = $conn->prepare("INSERT INTO user_club_memberships (user_id, role_id, club_id) VALUES (:user_id, :role_id, :club_id)");
                $addRole->execute([
                    ':user_id' => $userId,
                    ':role_id' => $role_id,
                    ':club_id' => $club_id
                ]);
            }
     
    // Kullanıcıyı onayla
    $approveStmt = $conn->prepare("UPDATE users SET is_approved = 1 WHERE id = :id");
    $approveStmt->execute([':id' => $userId]);
    $_SESSION['success'] = "Görevli başarıyla onaylandı ve kulübe atandı.";
        } else {
            $_SESSION['error'] = "Sadece görevli kullanıcılar admin onayı gerektirir.";
        }
    }
}
header("Location: ../admin/approve_users.php");
exit;