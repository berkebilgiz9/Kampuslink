<?php
session_start();
require_once '../config/db.php'; 


$inputData = json_decode(file_get_contents('php://input'), true);


$club_id = $inputData['club_id'] ?? null;
$user_id = $_SESSION['user']['id'] ?? null; 


if (!$club_id || !$user_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Eksik veri.'
    ]);
    exit;
}

try {
    
    $stmt = $conn->prepare("SELECT * FROM user_club_memberships WHERE user_id = ? AND club_id = ?");
    $stmt->execute([$user_id, $club_id]);
    $existingMembership = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingMembership) {
        
        echo json_encode([
            'success' => false,
            'message' => 'Zaten bu topluluğa katıldınız.'
        ]);
    } else {
        $roleStmt = $conn->prepare("SELECT id FROM roles WHERE role_name = 'öğrenci' LIMIT 1");
        $roleStmt->execute();
        $role_id = $roleStmt->fetchColumn();

        if (!$role_id) {
            throw new Exception("Rol bulunamadı.");
        }

        $stmt = $conn->prepare("INSERT INTO user_club_memberships (user_id, club_id, role_id) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $club_id, $role_id]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Topluluğa başarıyla katıldınız.'
        ]);
    }
} catch (PDOException $e) {
    
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
?>
