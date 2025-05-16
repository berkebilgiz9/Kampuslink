<?php
session_start();
require_once '../config/db.php';
 
if (!isset($_SESSION['user'])) {
  $_SESSION['error'] = "Etkinlikleri görüntülemek için giriş yapmalısınız!";
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user']['id'];

$clubId = $_GET['club_id'] ?? null;
$clubStmt = $conn->prepare("SELECT * FROM clubs WHERE id = :id");
$clubStmt->execute(['id' => $clubId]);
$club = $clubStmt->fetch(PDO::FETCH_ASSOC);

if (!$club) {
    $_SESSION['error'] = "Topluluk bulunamadı.";
    header("Location: clubs.php");
    exit;
}

$check = $conn->prepare("SELECT r.role_name FROM user_club_memberships ucm JOIN roles r ON ucm.role_id = r.id WHERE ucm.user_id = :uid AND ucm.club_id = :cid LIMIT 1");
$check->execute([':uid' => $user_id, ':cid' => $clubId]);
$role = $check->fetchColumn();

try{
$stmt = $conn->prepare("SELECT events.*, events.club_id,users.first_name, users.last_name FROM events LEFT JOIN users ON events.created_by = users.id WHERE events.club_id = :club_id 
ORDER BY event_date ASC");
$stmt->execute(['club_id' => $clubId]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $ex){
    $_SESSION['error'] = "Etkinlikler çekilirken bir hata oluştu!";
    $events = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($club['club_name']); ?> | Etkinlikler</title>
    <link rel="stylesheet" href="../css/club_events.css">
    <link rel="stylesheet" href="../css/dark_mode.css" />
</head> 
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h2 style="color: #ffd700;">♦ <?php echo htmlspecialchars($club['club_name']); ?> Etkinlikleri</h2>
    <p style="color:rgb(250, 250, 250);"><em><b>Fakülte:</b> <?php echo htmlspecialchars($club['faculty_name']); ?></em></p>

    <?php if (count($events) > 0): ?>
        <div class="events-wrapper">
        <?php foreach ($events as $event): ?>
            <div class="event-card">    
                <div class="event-title"><?php echo htmlspecialchars($event['title']); ?></div>
                <div class="event-date">📅  <?php echo date("d M Y", strtotime($event['event_date'])); ?></div>
                <div class="event-location">📍  <?php echo htmlspecialchars($event['location']); ?></div>
                <div class="event-desc"><?php echo nl2br(htmlspecialchars($event['description'])); ?></div>
                <div class="event-user">👤 Oluşturan: <?php echo htmlspecialchars($event['first_name'] . ' ' . $event['last_name']); ?></div>
                <div class="event-club">🏫 Gerçekleştiren Topluluk: <?php echo htmlspecialchars($club['club_name'] ?? '—'); ?></div>
                <?php if (!empty($event['file_path'])): ?>
                    <a class="file-link" href="../uploads/<?php echo htmlspecialchars($event['file_path']); ?>" target="_blank">📎 Dosyayı Görüntüle</a>
                <?php endif; ?>
          <a href="edit_event.php?id=<?= $event['id'] ?>">✏️ Güncelle</a>
          <a href="../actions/delete_event.php?id=<?= $event['id'] ?>" onclick="return confirm('Etkinliği silmek istediğine emin misin?')">🗑️ Sil</a>
            </div>
        <?php endforeach; ?>
            </div>
        <div class="back-button-wrapper">
                 <a href="../public/clubs.php" class="back-button">← Topluluklara Geri Dön</a>
        </div>
    <?php else: ?>
        <p>Bu topluluğa ait henüz bir etkinlik yok.</p>
    <?php endif; ?>
</div>
<script>
 
  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
   
    if (document.body.classList.contains('dark-mode')) {
      localStorage.setItem('theme', 'dark');
    } else {
      localStorage.setItem('theme', 'light');
    }
  }

  window.onload = function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.body.classList.add('dark-mode');
    }
  };
</script>
</body>
</html>
