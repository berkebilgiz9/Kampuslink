<?php
session_start();
require_once '../config/db.php';


$expr = $conn->query("SELECT DISTINCT faculty_name FROM clubs ORDER BY faculty_name ASC");
$faculties = $expr->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html> 
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fakülte Toplulukları</title>
    <link rel="stylesheet" href="../css/clubs.css" />
   
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include '../includes/header.php' ?>
<div class="container">
    <h2>🎓 Aradığınız Topluluk için Bir Fakülte Seçin</h2>
    <div class="faculties">
        <?php foreach ($faculties as $faculty): ?>
            <div class="faculty-card" data-faculty="<?php echo htmlspecialchars($faculty); ?>" onclick="loadClubs('<?php echo htmlspecialchars($faculty); ?>', 1)">
                <h3><i class="fas fa-university"></i> <?php echo htmlspecialchars($faculty); ?></h3>
            </div>
        <?php endforeach; ?> 
    </div>
            
    <!-- Modal -->
    <div id="warningModal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3>Giriş yapmadınız!</h3>
            <p>Lütfen giriş yapın veya Kayit olun.</p>
            <a href="../public/login.php" class="login-link">Giriş Yap</a>
            <a href="../public/Register.php" class="login-link">Kayit ol</a>
        </div>
    </div>
        
    <h3 id="selected-faculty" style="margin-top: 30px;"></h3>
    <div id="clubs-container"></div>
    <div id="pagination-container"></div>
</div>
<script src="../js/clubs.js"></script>
</body>
</html> 
