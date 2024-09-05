<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

// Veritabanına bağlanma
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

// Kullanıcı oturumunu başlat
session_start();

$response = [
    'status' => 'error',
    'message' => 'Bilinmeyen bir hata oluştu'
];

// Kullanıcının giriş yapıp yapmadığını kontrol et
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Giriş yapmadınız.']);
    exit(); // Giriş yapılmadıysa işlem durduruluyor
}

if (!isset($_SESSION['favs'])) {
    $_SESSION['favs'] = [];
}

if (isset($_POST['action'], $_POST['product_id']) && !empty($_POST['product_id'])) {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];

    if ($action === 'toggle') {
        if (isset($_SESSION['favs'][$product_id])) {
            unset($_SESSION['favs'][$product_id]);
            $response = [
                'status' => 'success',
                'action' => 'removed'
            ];
        } else {
            $_SESSION['favs'][$product_id] = true;
            $response = [
                'status' => 'success',
                'action' => 'added'
            ];
        }
    }
}

echo json_encode($response);
exit();
?>
