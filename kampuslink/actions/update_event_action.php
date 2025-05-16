<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    die("Yetkisiz giriş.");
}

$event_id = $_POST['event_id'] ?? null;
$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute([':id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Etkinlik bulunamadı.");
}

$check = $conn->prepare("SELECT r.role_name FROM user_club_memberships ucm join roles r on ucm.role_id = r.id WHERE ucm.user_id = :user_id AND ucm.club_id = :club_id");
$check->execute([':user_id' => $user_id, ':club_id' => $event['club_id']]);
$role = $check->fetchColumn();

if ($event['created_by'] != $user_id && !in_array(strtolower($role), ['görevli', 'admin'])) {
    die("Bu etkinliği düzenleme yetkiniz yok.");
}

$title = $_POST['title'];
$description = $_POST['description'];
$event_date = $_POST['event_date'];
$location = $_POST['location'];

$update = $conn->prepare("UPDATE events SET title = :title, description = :description, event_date = :event_date, location = :location WHERE id = :id");
$update->execute([
    ':title' => $title,
    ':description' => $description,
    ':event_date' => $event_date,
    ':location' => $location,
    ':id' => $event_id
]);

$_SESSION['success'] = "Etkinlik güncellendi.";
header("Location: ../public/club_events.php?club_id=" . $event['club_id']);
exit;
