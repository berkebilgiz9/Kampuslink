<?php
function userIsMember($clubId) {
    global $conn;

   
    if (!isset($_SESSION['user'])) {
        return false; 
    }

    $userId = $_SESSION['user']['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM club_members WHERE club_id = :club_id AND user_id = :user_id");
    $stmt->execute(['club_id' => $clubId, 'user_id' => $userId]);
    $count = $stmt->fetchColumn();

    return $count > 0;
}
?>