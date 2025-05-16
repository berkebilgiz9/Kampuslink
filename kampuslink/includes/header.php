
<?php define('BASE_URL', '/kampuslink/public/'); ?>
<header class="navbar">
    <a href="<?= BASE_URL ?>index.php" class="logo">
        <img src="<?= BASE_URL ?>../images/image.png" alt="Üniversite Logosu" style="height: 40px; margin-right: 10px;">
        <span>Kampüslink</span>
    </a>
    <nav>
        <ul>
            <li><a href="<?= BASE_URL ?>index.php">Ana Sayfa</a></li>
            <li><a href="<?= BASE_URL ?>login.php">Giriş Yap</a></li>
            <li><a href="<?= BASE_URL ?>clubs.php">Topluluklar</a></li>
            <li><a href="<?= BASE_URL ?>events.php">Etkinlikler</a></li>
            <li><a href="<?= BASE_URL ?>python/duyurular.php">Duyurular</a></li>
            <li><a href="<?= BASE_URL ?>profile.php">Profil</a></li>

            <li><button class="toggle-btn" onclick="toggleDarkMode()">🌙</button><li>
        </ul>
    </nav>
</header>
<script>
  function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode", document.body.classList.contains("dark-mode") ? "enabled" : "disabled");
  }

  
  document.addEventListener("DOMContentLoaded", () => {
    if (localStorage.getItem("darkMode") === "enabled") {
      document.body.classList.add("dark-mode");
    }
  });
</script>

