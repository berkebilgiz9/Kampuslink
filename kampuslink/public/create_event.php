<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Etkinlik oluşturmak için giriş yapmalısınız!";
    header("Location: login.php");
    exit;
}


try {
    $expr = $conn->query("SELECT id, club_name FROM clubs ORDER BY club_name ASC");
    $clubs = $expr->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $_SESSION['error'] = "Topluluklar yüklenirken hata oluştu!";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlik Oluştur</title>
    <link rel="stylesheet" href="../css/create_event_.css">
    <link rel="stylesheet" href="../css/dark_mode.css" />
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h2 style="color: #ffd700;">Yeni Etkinlik Oluştur</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <form action="../actions/create_event_action.php" method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <input type="text" name="title" id="title" placeholder="Etkinlik Başlığı" required>
        </div>
        
        <div class="input-group">
            <textarea name="description" id="description" rows="4" placeholder="Açıklama" required></textarea>
        </div>
        
        <div class="input-group">
            <input type="date" name="event_date" id="event_date" placeholder="Tarih" required>
        </div>
        
        <div class="input-group">
            <input type="text" name="location" id="location" placeholder="Yer" required>
        </div>
        
        <div class="input-group">
            <select name="club_id" id="club_id">
                <option value="">Topluluk Seç</option>
                <?php foreach ($clubs as $club): ?>
                    <option value="<?= $club['id']; ?>"><?= htmlspecialchars($club['club_name']); ?></option>
                <?php endforeach; ?>
            </select>
            
        </div>
        
        <div class="input-group">
            <input style="color: white;" type="file" name="attachment" accept=".pdf, .jpg, .jpeg, .png" placeholder="📂 Dosya Ekle (PDF/JPG/PNG)">
        </div>
        
        <button type="submit">Kaydet</button>
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
