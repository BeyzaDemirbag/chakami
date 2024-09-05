<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

// Veritabanına bağlanma
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

// Kullanıcı oturumunu başlatma
session_start();

// Sepet içeriğini kontrol etme
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Formdan gelen işlemi alma
    $action = $_POST['action'] ?? '';

    // Eğer işlem varsa devam et
    if (!empty($action)) {
        // İşlem tipine göre ayrıcalıklı işlemler gerçekleştir
        switch ($action) {
            case 'increase':
                // Ürün miktarını artır
                $product_id = $_POST['product_id'];
                $_SESSION['cart'][$product_id]['quantity']++;
                break;
            case 'decrease':
                // Ürün miktarını azalt, ancak minimum 1 adet olarak sınırla
                $product_id = $_POST['product_id'];
                if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                    $_SESSION['cart'][$product_id]['quantity']--;
                }
                break;
            case 'remove':
                // Ürünü sepetten kaldır
                $product_id = $_POST['product_id'];
                unset($_SESSION['cart'][$product_id]);
                break;
            default:
                // Tanınmayan işlem
                break;
        }
    }
}

echo '<pre>';
print_r($_POST);
echo '</pre>';



// Sepet sayfasına yönlendir
header("Location: sepet.php");
exit();
