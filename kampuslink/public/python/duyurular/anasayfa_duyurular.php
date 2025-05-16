<?php
$klasorYolu = __DIR__ . '/../duyurular/';
$jsonDosyalari = glob($klasorYolu . '*.json');

$downloadedImgFolder = $klasorYolu . 'downloaded_images/';
$downloadedImages = glob($downloadedImgFolder . '*');

$tumSecilenDuyurular = [];

function baslikUret($url) {
    $parsed = parse_url($url, PHP_URL_PATH);
    $parcalar = explode('/', trim($parsed, '/'));
    $kampusIndex = array_search('kampusun-sesi', $parcalar);
    if ($kampusIndex !== false && isset($parcalar[$kampusIndex + 1])) {
        return "Kampusun Sesi " . $parcalar[$kampusIndex + 1];
    }
    $sonParca = end($parcalar);
    $sonParca = preg_replace('/\.html?$/', '', $sonParca);
    $sonParca = urldecode($sonParca);
    return ucwords(str_replace(['-', '_'], ' ', $sonParca));
}

foreach ($jsonDosyalari as $dosya) {
    $icerik = file_get_contents($dosya);
    $duyurular = json_decode($icerik, true);
    if (!is_array($duyurular)) continue;
    
    shuffle($duyurular);
    $tumSecilenDuyurular = array_merge($tumSecilenDuyurular, array_slice($duyurular, 0, 2));
}

shuffle($tumSecilenDuyurular); 

?>

