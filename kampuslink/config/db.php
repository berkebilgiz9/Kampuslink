<?php
$host = 'localhost';        
$port = '5432';            
$dbname = 'postgres';       
$user = 'postgres';         
$password = '375390';    

// Veritabanı bağlantısını oluşturma
try{
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $conn->exec("SET search_path TO ogrenciToplulukBilgilendirme");
}
catch(PDOException $ex){
    die("Veritabanı bağlanamadı: ". $ex->getMessage());
}
?>