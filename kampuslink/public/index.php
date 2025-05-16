<?php
session_start();

$klasorYolu = __DIR__ . '/python/duyurular/';
$jsonDosyalari = glob($klasorYolu . '*.json');

$downloadedImgFolder = __DIR__ . '/python/duyurular/downloaded_images/';
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
}

$toplamDuyurular = [];
foreach ($jsonDosyalari as $jsonDosya) {
    $jsonData = json_decode(file_get_contents($jsonDosya), true);
    if (!is_array($jsonData)) continue;
    shuffle($jsonData);
    $toplamDuyurular = array_merge($toplamDuyurular, array_slice($jsonData, 0, 2));
}
shuffle($toplamDuyurular);
$toplamDuyurular = array_slice($toplamDuyurular, 0, 6);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Üniversite öğrencilerinin sosyal, kültürel ve akademik toplulukları keşfetmesini sağlayan dijital bir platformdur.">
    <meta name="keywords" content="üniversite, topluluklar, gruplar, etkinlikler, sosyal ağ, kültür, eğitim">

    <title>Kampüslink | Üniversite Toplulukları Bilgilendirme Platformu</title>
    <link rel="stylesheet" href="../css/index_.css" />
    <link rel="stylesheet" href="../css/dark_mode.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
</head>

<body>
    <?php include '../includes/header.php'; ?>
     
    <section class="hero" data-aos="fade-up">
        <div class="hero-content">
            <h1 data-aos="fade-down">Üniversite Gruplarına Katılın!</h1>
            <p data-aos="fade-up">İlgi alanınıza göre gruplar bulun ve etkinliklere katılın.</p>
            <a href="clubs.php" class="btn">Topluluklara Göz Gezdirin</a>
        </div>
    </section>


    <section id="aciklama" class="description" data-aos="fade-up">
        <h2 id="baslik1">Kampüslink Nedir?</h2>
        <p id="paragraf">
            🎓 Kampüslink, üniversite öğrencilerinin sosyal, kültürel ve akademik toplulukları keşfetmesini sağlayan dijital bir platformdur.
        </p>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-right">
                <h3>🔍 Keşfet</h3>
                <p>İlgi alanlarına uygun toplulukları ve kulüpleri kolayca bul.</p>
            </div>
            <div class="feature-card" data-aos="fade-up">
                <h3>📅 Etkinlik Takibi</h3>
                <p>Kulüplerin düzenlediği etkinlikleri takip et, kaçırma.</p>
            </div>
            <div class="feature-card" data-aos="fade-left">
                <h3>🤝 Sosyal Ağ</h3>
                <p>Topluluklarla iletişim kur, iş birliklerine katıl.</p>
            </div>
            <div class="feature-card" data-aos="fade-down">
                <h3>🚀 Katılım</h3>
                <p>Projelerde yer al, topluluklara üye ol ve aktif rol oyna.</p>
            </div>
        </div>
    </section>


    <section class="duyuru-blok">
    <h2 style="color: #ffd700;">Rastgele Duyurular</h2>
        <div class="grid">
            <?php foreach ($toplamDuyurular as $duyuru): ?>
                <?php
                    $gorsel = !empty($duyuru['img']) ? $duyuru['img'] : 'python/img/default.png';
                    $defaultClass = empty($duyuru['img']) ? 'default-img' : '';
                    $title = !empty($duyuru['title']) ? $duyuru['title'] : baslikUret($duyuru['link']);

                    $imgName = basename($gorsel);
                    $imgExtensions = ['jpg', 'jpeg', 'png'];

                    foreach ($imgExtensions as $ext) {
                        $imagePath = $downloadedImgFolder . $imgName;
                        if (in_array($imagePath, $downloadedImages)) {
                            $gorsel = 'python/duyurular/downloaded_images/' . $imgName;
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

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
        });
    </script>
    

</body>
</html>
