<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

// Veritabanına bağlanma ve karakter setini ayarlama
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

// Oturumu başlat
session_start();

// Kullanıcı oturumunu kontrol et
if (isset($_SESSION["email"])) {
    // Kullanıcı oturumu varsa, ödeme sayfasına yönlendirilecek URL
    $redirect_url = "odeme.php";
} else {
    // Kullanıcı oturumu yoksa, giriş sayfasına yönlendirilecek URL
    $redirect_url = "login.php";
}

// Toplam fiyatı sıfırla
$total_price = 0;

// Sepet öğelerini varsa al
$cart_items = $_SESSION['cart'] ?? [];
?>




<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sepetim </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/sepet.css">
</head>

<body>
    <!-- Navigasyon çubuğu -->
    <nav class="navbar">

    <div class="logo">
            <a href="anasayfa.php"><img src="assets/img/duz-yesil-logo.png" alt="Logo"></a>
        </div>

       <!-- Arama çubuğu -->
       <div class="search-bar">
            <!-- Arama inputu -->
            <input type="text" id="search" class="form-control" placeholder="Arama..." onkeyup="liveSearch()">
            <!-- Arama ikonu -->
            <img src="assets/img/glass.png" alt="Arama" class="search-icon">
        </div>
        <!-- Dinamik olarak arama sonuçlarını gösterecek alan -->
<div id="result"></div>
        
        <div class="user-actions">
            <a href="favoriler.php"><img src="assets/img/favs.png" alt="Favoriler"></a>
            <div class="profile-popup">
                <a href="profile.php"><img src="assets/img/profile.png" alt="Üye Girişi"></a>
               <!-- Profil popup içeriği -->
<div class="profile-popup-content">
    <?php
    if (isset($_SESSION["email"])) {
        // Eğer oturum açılmışsa sadece Çıkış Yap linkini göster
        echo "<a href='cikis.php'>Çıkış Yap</a>";
    } else {
        // Eğer oturum açılmamışsa Üye Ol ve Giriş Yap linklerini göster
        echo "<a href='kayit.php'>Üye Ol</a>";
        echo "<a href='login.php'>Giriş Yap</a>";
    }
    ?>
</div>
            </div>
            <a href="sepet.php"><img src="assets/img/basket.png" alt="Sepet"></a>
        </div>
    </nav>
    <!-- Kategori Butonları -->
    <div class="categories">
        <div class="row justify-content-center">
            <div class="col-md-auto"><a href="beyazcay.php" class="btn btn-link">Beyaz Çay</a></div>
            <div class="col-md-auto"><a href="yesilcay.php" class="btn btn-link">Yeşil Çay</a></div>
            <div class="col-md-auto"><a href="oolong.php" class="btn btn-link">Oolong</a></div>
            <div class="col-md-auto"><a href="siyahcay.php" class="btn btn-link">Siyah Çay</a></div>
            <div class="col-md-auto"><a href="bitki-meyve.php" class="btn btn-link">Bitki & Meyve Çayları</a></div>
        </div>
    </div>

    <div class="custom-container">
        <h2>Sepetim</h2>
        <?php
        // Sepet öğelerini döngü ile gösterme
        foreach ($cart_items as $product_id => $item) {
            // Ürün kimliği sayısal değilse, döngüyü atla
            if (!is_numeric($product_id)) {
                continue;
            }

            // Ürünü veritabanından al
            $sql = "SELECT * FROM urun_liste WHERE ID = $product_id";
            $result = mysqli_query($baglanti, $sql);
            // Veritabanı sorgusu başarılı ve sonuç varsa
            if ($result && mysqli_num_rows($result) > 0) {
                // Her bir ürün için kart oluşturma
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="custom-product-item">';
                    echo '<img src="' . $row['product_url'] . '" alt="' . $row['product_name'] . '">';
                    echo '<div class="item-info">';
                    echo '<h2>' . $row['product_name'] . '</h2>';
                    echo '<p>' . $row['price'] . '₺</p>';
                    echo '</div>'; // item-info kapanışı
                    echo '<div class="product-actions">';
                    // Ürün miktarını arttırma ve azaltma formu
                    echo '<form method="post" action="update_cart.php">';
                    echo '<button class="decrement" type="submit" name="action" value="decrease">-</button>';
                    echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                    echo '<input type="text" class="quantity" name="quantity" value="' . $item['quantity'] . '">';
                    echo '<button class="increment" type="submit" name="action" value="increase">+</button>';
                    echo '</form>';
                    // Ürünün toplam fiyatını gösterme
                    echo '<p class="total-price">' . ($row['price'] * $item['quantity']) . '₺</p>';
                    // Ürünü sepetten kaldırma formu
                    echo '<form method="post" action="update_cart.php">';
                    echo '<button class="remove" type="submit" name="action" value="remove"><img src="assets/img/trash-button.png" alt="Sil"></button>';
                    echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                    echo '</form>';
                    echo '</div>'; // product-actions kapanışı
                    echo '</div>'; // custom-product-item kapanışı
                    // Toplam tutarı güncelleme
                    $total_price += ($row['price'] * $item['quantity']);
                }
            }
        }
        // Eğer sepette ürün yoksa mesaj göster
        if (empty($cart_items)) {
            echo '<div class="custom-product-item">Sepetinizde ürün bulunmamaktadır.</div>';
        }
        ?>
         </div>
         
        <!-- Toplam tutar -->
        <div class="checkout">
            <!-- Toplam tutar burada dinamik olarak güncellenir -->
            <h3>Toplam Tutar: <?php echo $total_price; ?> ₺</h3>
            <?php if (isset($_SESSION['email']) && $total_price > 0): ?>
    <button style="border: 1px solid #ccc;" onclick="window.location.href='<?php echo $redirect_url; ?>'">Ödeme Yap</button>
<?php elseif (!isset($_SESSION['email'])): ?>
    <button style="border: 1px solid #ccc;" onclick="window.location.href='login.php'">Giriş Yap</button>
<?php else: ?>
    <button style="border: 1px solid #ccc;" disabled> Ödeme Yap </button>
<?php endif; ?>

        </div>
    
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Chakami </p>
        </div>
    </footer>

    <script src="assets/js/sepet.js"></script>

</body>

</html>