<?php
session_start();

$klasorYolu = __DIR__ . '/duyurular/';
$jsonDosyalari = glob($klasorYolu . '*.json');

$downloadedImgFolder = __DIR__ . '/duyurular/downloaded_images/';
$downloadedImages = glob($downloadedImgFolder . '*');

function baslikUret($url) {
    $parsed = parse_url($url, PHP_URL_PATH);
    $parcalar = explode('/', trim($parsed, '/'));

    $kampusIndex = array_search('kampusun-sesi', $parcalar);
    if ($kampusIndex !== false && isset($parcalar[$kampusIndex + 1])) {
        $bolumNo = $parcalar[$kampusIndex + 1];
        return "Kampusun Sesi " . $bolumNo;
    }

    $sonParca = end($parcalar);
    $sonParca = preg_replace('/\.html?$/', '', $sonParca);
    $sonParca = urldecode($sonParca);
    $sonParca = str_replace(['-', '_'], ' ', $sonParca);
    return ucwords($sonParca);
    $allData = [];

foreach ($jsonDosyalari as $jsonDosya) {
    $jsonData = json_decode(file_get_contents($jsonDosya), true);

    
    shuffle($jsonData);
    $allData = array_merge($allData, array_slice($jsonData, 0, 2)); 
}
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <link rel="stylesheet" href="/kampuslink/css/duyurular_.css" />
  <meta charset="UTF-8">
  <title>Duyurular</title>
</head>
<body>
<?php


    include '../../includes/header.php';

?>
  <h1>Duyurular</h1>

<?php foreach ($jsonDosyalari as $dosya): ?>
  <?php
    $icerik = file_get_contents($dosya);
    $duyurular = json_decode($icerik, true);

    if (!is_array($duyurular) || empty($duyurular)) continue;

    // JSON dosyasının adı -> başlık 
    $dosyaAdi = basename($dosya, '.json'); 
    $dosyaAdi = preg_replace('/[-_]+/', ' ', $dosyaAdi); 
    $dosyaBaslik = preg_replace('/\.html?$/', '', $dosyaAdi); 
    $dosyaBaslik = ucwords($dosyaBaslik); 
    
  ?>

  <section class="duyuru-blok">
    <h2><?= htmlspecialchars($dosyaBaslik) ?></h2>
    <div class="grid">
    <?php foreach ($duyurular as $duyuru): ?>
      <?php
        $gorsel = !empty($duyuru['img']) ? $duyuru['img'] : 'img/default.png';
        $defaultClass = empty($duyuru['img']) ? 'default-img' : '';
        $title = !empty($duyuru['title']) ? $duyuru['title'] : baslikUret($duyuru['link']);

        $imgName = basename($gorsel);
        $imgExtensions = ['jpg', 'jpeg', 'png'];

        foreach ($imgExtensions as $ext) {
            $imagePath = $downloadedImgFolder . $imgName;
            if (in_array($imagePath, $downloadedImages)) {
                $gorsel = 'duyurular/downloaded_images/' . $imgName;
                break;
            }
        }
      ?>
      <div class="duyuru">
        <a href="<?= htmlspecialchars($duyuru['link']) ?>" target="_blank">
          <img src="<?= htmlspecialchars($gorsel) ?>" alt="Duyuru Görseli" class="<?= $defaultClass ?>">
          <p class="duyuru-baslik"><?= htmlspecialchars($title) ?></p>
        </a>
      </div>
    <?php endforeach; ?>
    </div>
  </section>

<?php endforeach; ?>
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
