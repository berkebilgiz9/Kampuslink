<?php
session_start();
require_once '../config/db.php';

// Kullanıcı rolleri sayısı
$countStmt = $conn->prepare("
    SELECT 
        SUM(CASE WHEN role = 'öğrenci' AND is_approved = 1 THEN 1 ELSE 0 END) AS student_count,
        SUM(CASE WHEN role = 'görevli' AND is_approved = 1 THEN 1 ELSE 0 END) AS staff_count,
        SUM(CASE WHEN role = 'admin' AND is_approved = 1 THEN 1 ELSE 0 END) AS admin_count
    FROM users
");
$countStmt->execute();
$counts = $countStmt->fetch(PDO::FETCH_ASSOC);


// Tüm kulüpler
$clubsStmt = $conn->prepare("SELECT id, club_name FROM clubs");
$clubsStmt->execute();
$clubs = $clubsStmt->fetchAll(PDO::FETCH_ASSOC);

// Admin kontrolü 
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    $_SESSION['error'] = "Bu sayfaya erişmek için admin olmalısınız!";
    header("Location: ../public/login.php"); 
    exit;
}
  
// Onay bekleyen kullanıcılar
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE is_approved = 0");
$stmt->execute();
$pendingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
 
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Kullanıcı Onayı</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include '../includes/header.php' ?>
    <div style="margin: 20px 0; background-color: #f4f4f4; padding: 15px; border-radius: 8px;">
    <h3 style="margin-top: 0; color: #333;">Onaylanmış Kullanıcı Sayıları</h3>
    <ul style="list-style-type: none; padding-left: 0; color: black;">
        <li>👨‍🎓 Öğrenci: <strong><?= $counts['student_count'] ?></strong></li>
        <li>🧑‍💼  Görevli: <strong><?= $counts['staff_count'] ?></strong></li>
        <li>🛡️ Admin: <strong><?= $counts['admin_count'] ?></strong></li>
    </ul>
    </div>
    <?php
// Onaylanmış kullanıcılar
$approvedUsersStmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE is_approved = 1 and role != 'admin'");
$approvedUsersStmt->execute();
$approvedUsers = $approvedUsersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="color: #dc143c;">Onaylı Kullanıcıları Sil</h2>

<?php if (empty($approvedUsers)): ?>
    <p>Hiç onaylı kullanıcı bulunmamaktadır.</p>
<?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Ad</th>
            <th>Soyad</th>
            <th>E-posta</th>
            <th>Rol</th> 
            <th>İşlem</th>
        </tr>
        <?php foreach ($approvedUsers as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['first_name']) ?></td>
                <td><?= htmlspecialchars($user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <form action="../actions/delete_user_action.php" method="post" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" style="background-color: darkred; color: white;">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

    <h2 style="color: #ffd700;">Onay Bekleyen Kullanıcılar</h2>
    
    <?php if (empty($pendingUsers)): ?>
        <p style="color:rgb(255, 0, 0);">Onay bekleyen kullanıcı bulunmamaktadır.</p>
    <?php else: ?>
        <?php if (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Ad</th>
                <th>Soyad</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>İşlem</th>
            </tr>
            <?php foreach ($pendingUsers as $user): ?>
            
                <tr>
                    <td><?= htmlspecialchars($user['first_name']) ?></td>
                    <td><?= htmlspecialchars($user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
    <form action="../actions/approve_user_action.php" method="post" style="display:inline;" onsubmit="return confirm('Bu kullanıcıyı onaylamak istiyor musunuz?');">
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <input type="hidden" name="action" value="approve">
        <?php if ($user['role'] === 'görevli'): ?>
        <label for="club_id_<?= $user['id'] ?>">Kulüp Seç:</label>
        <select name="club_id" id="club_id_<?= $user['id'] ?>" required>
            <option value="">-- Seçiniz --</option>
            <?php foreach ($clubs as $club): ?>
                <option value="<?= $club['id'] ?>"><?= htmlspecialchars($club['club_name']) ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
        <button type="submit">Onayla</button>
    </form>
    
    <form action="../actions/approve_user_action.php" method="post" style="display:inline;" onsubmit="return confirm('Bu kullanıcıyı reddetmek istiyor musunuz?');">
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <input type="hidden" name="action" value="reject">
        <button type="submit" style="background-color: red; color: white;">Reddet</button>
    </form>
</td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
