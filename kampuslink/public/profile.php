<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Önce giriş yapınız!";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id']; 


$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Kulüp + Etkinlik verileri tek sorguda alma
$clubStmt = $conn->prepare("
    SELECT 
        c.id AS club_id, c.club_name, c.faculty_name,
        e.id AS event_id, e.title, e.description, e.event_date, e.location
    FROM 
        clubs c
    JOIN 
        user_club_memberships ucm ON c.id = ucm.club_id
    LEFT JOIN 
        events e ON c.id = e.club_id
    WHERE 
        ucm.user_id = ?
    ORDER BY 
        c.id, e.event_date DESC
");
$clubStmt->execute([$user_id]);
$results = $clubStmt->fetchAll(PDO::FETCH_ASSOC);


$clubs = [];
foreach ($results as $row) {
    $club_id = $row['club_id'];

    if (!isset($clubs[$club_id])) {
        $clubs[$club_id] = [
            'id' => $club_id,
            'club_name' => $row['club_name'],
            'faculty_name' => $row['faculty_name'],
            'events' => []
        ];
    }

    if (!empty($row['event_id'])) {
        $clubs[$club_id]['events'][] = [
            'id' => $row['event_id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'event_date' => $row['event_date'],
            'location' => $row['location']
        ];
    }
}

// Sıralı dizi versiyonu
$clubs = array_values($clubs);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/dark_mode.css" />
    
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="profile-container">
    <h2>👤 Profil Bilgileri</h2>
    <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
    <?php if (count($clubs) > 0): ?>
        <h3>📌 Katıldığınız Topluluklar</h3>
        <?php foreach ($clubs as $club): ?>
            <div class="club-info">
                <h4><?= htmlspecialchars($club['club_name']) ?> <small>(<?= htmlspecialchars($club['faculty_name']) ?>)</small></h4>

                <p><strong>📅 Etkinlikler:</strong></p>
                <?php if (count($club['events']) > 0): ?>
                    <ul class="event-list">
                        <?php foreach ($club['events'] as $event): ?>
                            <li>
                                <strong><?= htmlspecialchars($event['title']) ?></strong> - 
                                <?= htmlspecialchars(date("d.m.Y", strtotime($event['event_date']))) ?><br>
                                <?= htmlspecialchars($event['location']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Bu topluluk henüz etkinlik oluşturmadı.</p>
                <?php endif; ?>

            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>  
        <p>Herhangi bir topluluğa ait değilsiniz.</p>
    <?php endif; ?>
    <div class="logout-section">
    <form action="../public/logout.php" method="post">
            <button type="submit" class="logout-button">🔓 Çıkış Yap</button>
    </form>
    </div>
    <a href="../public/change_password.php" class="logout-button" style="background-color:#007bff; margin-left:10px;">🔑 Şifre Değiştir</a>
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

