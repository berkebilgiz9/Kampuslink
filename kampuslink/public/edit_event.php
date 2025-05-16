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
$check = $conn->prepare("SELECT r.role_name FROM user_club_memberships ucm join roles r on ucm.role_id = r.id WHERE ucm.user_id = :user_id AND ucm.club_id = :club_id LIMIT 1");
$check->execute([':user_id' => $user_id, ':club_id' => $event['club_id']]);
$role = $check->fetchColumn();


$clubs = $conn->query("SELECT id, club_name FROM clubs")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlik Düzenle</title>
    <link rel="stylesheet" href="../css/create_event_.css">
    <link rel="stylesheet" href="../css/dark_mode.css" />
</head>
<body>
<?php include '../includes/header.php'; ?>
<h2 style="color: #ffd700;">Etkinlik Düzenle</h2>

<form action="../actions/update_event_action.php" method="POST">
    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

    <div class="input-group">
    <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required><br>
        </div>
        
        <div class="input-group">
        <textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea><br>
        </div>
        
        <div class="input-group">
        <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required><br>
        </div>
        
        <div class="input-group">
        <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required><br>
        </div>
        
        <div class="input-group">
            <select name="club_id" id="club_id">
            <?php foreach ($clubs as $club): ?>
              <option value="<?= $club['id']; ?>" <?= $club['id'] == $event['club_id'] ? 'selected' : '' ?>>
               <?= htmlspecialchars($club['club_name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
            
        </div>
                  
        <button type="submit">Güncelle</button>
</form>
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
