<?php
session_start();
require_once '../config/db.php';

$user_id = $_SESSION['user']['id'];
$clubId = $_GET['club_id'] ?? null;
$check = $conn->prepare("SELECT r.role_name FROM user_club_memberships ucm JOIN roles r ON ucm.role_id = r.id WHERE ucm.user_id = :uid AND ucm.club_id = :cid LIMIT 1");
$check->execute([':uid' => $user_id, ':cid' => $clubId]);
$role = $check->fetchColumn();

$event_id = $_GET['id'] ?? null;
$stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute([':id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if ($event['created_by'] !== $user_id || in_array($role, ['öğrenci'])){
    $_SESSION['error'] = "Etkinlik Düzenleyemezsiniz!";
    header("Location: events.php");
    exit;
}

// Yetki kontrolü
$check = $conn->prepare("SELECT r.role_name FROM user_club_memberships ucm join roles r on ucm.role_id = r.id WHERE ucm.user_id = :user_id AND ucm.club_id = :club_id");
$check->execute([':user_id' => $user_id, ':club_id' => $event['club_id']]);
$role = $check->fetchColumn();

if ($event['created_by'] != $user_id && !in_array($role, ['görevli', 'admin'])) {
    die("Bu etkinliği silme yetkiniz yok.");
}

// Silme
$delete = $conn->prepare("DELETE FROM events WHERE id = :id");
$delete->execute([':id' => $event_id]);

$_SESSION['success'] = "Etkinlik silindi.";
header("Location: ../public/club_events.php?club_id=" . $event['club_id']);

exit;
