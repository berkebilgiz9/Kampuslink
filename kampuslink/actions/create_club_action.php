<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Kulüp oluşturmak için giriş yapmalısınız!";
    header("Location: ../public/login.php");
    exit;
}

$club_name = trim($_POST['club_name']);
$faculty_name = trim($_POST['faculty_name']);

try {
    $insert = $conn->prepare("INSERT INTO clubs (club_name, faculty_name) 
                              VALUES (:club_name, :faculty_name)");
    $insert->bindParam(':club_name', $club_name);
    $insert->bindParam(':faculty_name', $faculty_name);
    $insert->execute();

    $_SESSION['success'] = "Topluluk başarıyla eklendi!";
    header("Location: ../public/clubs.php");
} catch (PDOException $ex) {
    $_SESSION['error'] = "Kulüp oluşturulurken bir hata oluştu: " . $ex->getMessage();
    header("Location: ../public/create_club.php");
}
?>
