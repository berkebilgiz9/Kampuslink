<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Etkinlik oluşturmak için giriş yapmalısınız!";
    header("Location: ../public/login.php");
    exit;
}
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$event_date = $_POST['event_date'] ?? '';
$location = trim($_POST['location'] ?? '');
$user_id = $_SESSION['user']['id'];
$club_id = !empty($_POST['club_id']) ? $_POST['club_id'] : null;
$attachmentPath = null;
/*if (!$club_id) {
    $_SESSION['error'] = "Etkinlik oluşturmak için bir topluluk seçmelisiniz.";
    header("Location: ../public/create_event.php");
    exit;
}*/
// Dosya yükleme 
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['attachment']['tmp_name'];
    $fileName = basename($_FILES['attachment']['name']);
    $fileSize = $_FILES['attachment']['size'];
    $fileType = mime_content_type($fileTmpPath);

    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['error'] = "Yalnızca PDF, JPEG veya PNG tipinde dosya yüklenebilir.";
        header("Location: ../public/create_event.php");
        exit;
    }

    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $uploadDir = '../uploads/';
    $destPath = $uploadDir . uniqid() . "_" . $fileExtension;
    $newFileName = uniqid('event_', true) . '.' . $fileExtension;
    $destPath2 = $uploadDir . $newFileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $attachmentPath = $destPath;
    } else {
        $_SESSION['error'] = "Dosya yüklenemedi!";
        header("Location: ../public/create_event.php");
        exit;
    }
}

try {
    $check = $conn->prepare("
    SELECT r.role_name 
    FROM user_club_memberships ucm
    JOIN roles r ON ucm.role_id = r.id
    WHERE ucm.user_id = :user_id AND ucm.club_id = :club_id
    LIMIT 1
");
$check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$check->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$check->execute();

$result = $check->fetch(PDO::FETCH_ASSOC);

if (!$result || !in_array($result['role_name'], ['görevli', 'admin'])) {
    $_SESSION['error'] = "Sadece topluluk görevlisi veya admin kullanıcılar etkinlik oluşturabilir.";
    header("Location: ../public/create_event.php");
    exit;
}
    
    $insert = $conn->prepare("INSERT INTO events (title, description, event_date, location, created_by, file_path, club_id) VALUES (:title, :description, :event_date, :location, :created_by, :file_path, :club_id)");
    $insert->bindParam(':title', $title);
    $insert->bindParam(':description', $description);
    $insert->bindParam(':event_date', $event_date);
    $insert->bindParam(':location', $location);
    $insert->bindParam(':created_by', $user_id);
    $insert->bindParam(':file_path', $attachmentPath);
    $insert->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $insert->execute();

    $_SESSION['success'] = "Etkinlik başarıyla oluşturuldu!";
    header("Location: ../public/create_event.php");
    exit;
 
} catch (PDOException $ex) {
    $_SESSION['error'] = "Etkinlik oluşturulurken bir hata oluştu: " . $ex->getMessage();
    header("Location: ../public/create_event.php");
}