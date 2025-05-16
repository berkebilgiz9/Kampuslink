<?php
session_start();
require_once '../config/db.php'; 

if (!isset($_SESSION['user'])) {
    echo json_encode(['isLoggedIn' => false]);
    exit;
}

$userId = $_SESSION['user']['id']; 


$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$user || $user['id'] != $userId) {
    echo json_encode(['isLoggedIn' => false]);
} else {
    echo json_encode(['isLoggedIn' => true]);
}
?>
