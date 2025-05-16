<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Topluluk eklemek için giriş yapmalısınız!";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Topluluk Ekleme</title>
    <link rel="stylesheet" href="../css/dark_mode.css" />
</head>
<body>
    <h2>Topluluk Ekle</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <form action="../actions/create_club_action.php" method="POST">
        <label>Topluluk Adı:</label><br>
        <input type="text" name="club_name" required><br><br>

        <label>Fakülte:</label><br>
        <input type="text" name="faculty_name" required><br><br>

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
