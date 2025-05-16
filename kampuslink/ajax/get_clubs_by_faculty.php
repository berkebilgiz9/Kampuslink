<?php
require_once '../config/db.php';
session_start();
$isLoggedIn = isset($_SESSION['user']);

//Pagination 
$faculty = $_GET['faculty'] ?? null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$response = ['clubs' => [], 'totalPages' => 1, 'currentPage' => $page];

if ($faculty) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM clubs WHERE faculty_name = :faculty");
    $stmt->execute(['faculty' => $faculty]);
    $total = $stmt->fetchColumn();
    $response['totalPages'] = ceil($total / $limit);

    $stmt = $conn->prepare("SELECT * FROM clubs WHERE faculty_name = :faculty ORDER BY club_name ASC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':faculty', $faculty);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $response['clubs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($response);
