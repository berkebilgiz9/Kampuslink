<?php
session_start();
require_once '../config/db.php';
 
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT e.*, c.club_name 
                        FROM events e 
                        LEFT JOIN clubs c ON e.club_id = c.id 
                        WHERE event_date >= :today 
                        ORDER BY event_date ASC 
                        LIMIT 10");
$stmt->bindParam(':today', $today);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <title>Etkinlikler</title>
  <link rel="stylesheet" href="../css/events_.css?v=2">
  <link rel="stylesheet" href="../css/dark_mode.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

  <!-- Tilt.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

  <!-- Lottie -->
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
 
<body>
  <?php include '../includes/header.php'; ?>

  <div class="events-container">
    <h2>🎯 Yaklaşan Etkinlikler</h2>

    <!-- Lottie Animasyonu -->
    <div class="lottie-wrapper">
      <lottie-player 
        src="https://assets9.lottiefiles.com/private_files/lf30_m6j5igxb.json" 
        background="transparent" 
        speed="1" 
        style="width: 250px; height: 250px;" 
        loop 
        autoplay>
      </lottie-player>
    </div>

    <?php if (count($events) > 0): ?>
      <div class="event-scroll">
        <?php foreach ($events as $event): ?>
          <div class="event-card" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.2">
            <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
            <p><strong>📅 Tarih:</strong> <?php echo date('d M Y', strtotime($event['event_date'])); ?></p>
            <p><strong>📍 Yer:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <?php if ($event['club_name']): ?>
              <p><strong>🏫 Topluluk:</strong> <?php echo htmlspecialchars($event['club_name']); ?></p>
            <?php endif; ?>
            <p class="event-description"><strong>Açıklama:</strong> <?php echo mb_strimwidth(htmlspecialchars($event['description']), 0, 100, '...'); ?></p>
            <?php if (!empty($event['file_path'])): ?>
              <p><a class="file-link" href="../uploads/<?php echo htmlspecialchars($event['file_path']); ?>" target="_blank">📎 Dosyayı Görüntüle</a></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Yaklaşan etkinlik bulunmamaktadır.</p>
    <?php endif; ?>

    
    <div class="create-btn-container">
      <a href="create_event.php" class="create-event-btn" id="create-event-btn">Etkinlik Oluştur</a>
      <div class="login-warning">Etkinlik oluşturmak için giriş yapmalısınız.</div>
    </div>
  </div>

  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $.ajax({
        url: '../ajax/check_login_.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.isLoggedIn) {
            $('#create-event-btn').show();
            $('#login-warning').hide();
          } else {
            $('#create-event-btn').hide();
          }
        },
        error: function(xhr, status, error) {
          console.error("Giriş kontrol hatası:", error);
        }
      });

      // VanillaTilt Etkisi
      VanillaTilt.init(document.querySelectorAll(".event-card"));
    });
  </script>

  <!-- Dark Mode -->
  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
      localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
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
