<?php session_start(); ?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kayıt Ol</title>
  <link rel="stylesheet" href="../css/register_.css?v=2" />
  <link rel="stylesheet" href="../css/dark_mode.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <?php include '../includes/header.php'; ?>
  <div class="container">
    <div class="auth-box">
      <div class="form-container">

    
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <h2>Kayıt Ol</h2>

        <form action="../actions/register_action.php" method="post" class="auth-form">
          <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="first_name" placeholder="Adınızı girin" required />
          </div>

          <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="last_name" placeholder="Soyadınızı girin" required />
          </div>

          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="E-posta adresinizi girin" required />
          </div>

          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Şifrenizi girin" required />
          </div>

          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password_confirm" placeholder="Şifrenizi tekrar girin" required />
          </div>

          <div class="input-group">
  <i class="fas fa-user-tag"></i>
  <select name="role" required>
    <option value="öğrenci">Öğrenci</option>
    <option value="görevli">Görevli</option>
  </select>
</div>

          <button type="submit" class="btn">Kayıt Ol</button>
          <p>Zaten hesabın var mı? <a href="login.php">Giriş Yap</a></p>
        </form>

      </div>
    </div>
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
