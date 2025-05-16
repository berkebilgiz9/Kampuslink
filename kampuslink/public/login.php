<?php
session_start();

$loginStatus = ['isLoggedIn' => false];
$checkLoginPath = '../ajax/check_login_.php';

if (file_exists($checkLoginPath)) {
    $response = file_get_contents($checkLoginPath);
    $loginStatus = json_decode($response, true);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giriş Yap</title>
  <link rel="stylesheet" href="../css/login_.css?v=2" />
  <link rel="stylesheet" href="../css/dark_mode.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
 
<?php include '../includes/header.php'; ?>
<div class="login-section">
  <div class="login-container">
    <h2>Giriş Yap</h2>

    <div id="login-status-message" style="display: none;">
      <div color="black" class="alert alert-info">
        Zaten giriş yaptınız. <a href="index.php">Ana sayfaya dön</a>.
      </div>
    </div>

    <div id="login-form-container">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>

      <form action="../actions/login_action.php" method="post" class="login-form">
        <div class="input-icon">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" class="form-control" placeholder="E-posta adresiniz" required>
        </div>
        <div class="input-icon">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" class="form-control" placeholder="Şifreniz" required>
        </div>
        <button type="submit" class="btn w-100">Giriş Yap</button>
        <p>Hesabın yok mu? <a href="register.php">Kayıt Ol</a></p>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $.ajax({
      url: '../ajax/check_login_.php',
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.isLoggedIn) {
          $('#login-status-message').show();
          $('#login-form-container').hide();
        } else {
          $('#login-status-message').hide();
          $('#login-form-container').show();
        }
      },
      error: function (xhr, status, error) {
        console.error("Giriş kontrol hatası:", error);
      }
    });
  });

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
