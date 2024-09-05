<?php
// Oturumu başlat
session_start();

// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

// Veritabanına bağlan
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

// Veritabanı bağlantısının kontrolü
if (!$baglanti) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

// Bitki ve meyve çaylarını veritabanından seç
$sql = "SELECT * FROM urun_liste WHERE product_type = 'Bitki ve Meyve'";
$result = mysqli_query($baglanti, $sql);

// Sepete ürün ekleme işlemi
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    // Eğer ürün zaten sepete eklenmişse, adetini artır
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // Yeni bir ürün ekleyin
        $_SESSION['cart'][$product_id] = array('quantity' => 1);
    }
    // Sepete ürün eklendiğinde bir uyarı göster
    echo '<script>alert("Ürün sepete eklendi.");</script>';
}

// Favorilere ürün ekleme işlemi
if (isset($_POST['add_to_favs'])) {
    $product_id = $_POST['product_id'];
    // Eğer ürün zaten favorilere eklenmişse, mesaj göster
    if (isset($_SESSION['favs'][$product_id])) {
        echo "Ürün zaten favorilerinizde.";
    } else {
        // Yeni bir ürün ekle
        $_SESSION['favs'][$product_id] = array('quantity' => 1);
        echo "Ürün favorilere eklendi.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Bitki & Meyve Çayları | Chakami </title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/bitki-meyve.css">
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

        <!-- Kullanıcı işlemleri -->
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
    <div class="container categories">
        <div class="row justify-content-center">
            <div class="col-md-auto"><a href="beyazcay.php" class="btn btn-link">Beyaz Çay</a></div>
            <div class="col-md-auto"><a href="yesilcay.php" class="btn btn-link">Yeşil Çay</a></div>
            <div class="col-md-auto"><a href="oolong.php" class="btn btn-link">Oolong</a></div>
            <div class="col-md-auto"><a href="siyahcay.php" class="btn btn-link">Siyah Çay</a></div>
            <div class="col-md-auto"><a href="bitki-meyve.php" class="btn btn-link">Bitki & Meyve Çayları</a></div>
        </div>
    </div>

    <div class="container">
        <h1 class="text-center my-4">Bitki ve Meyve Çayları</h1>
        <div class="row">
            <!-- Ürün kartlarını döngü ile oluşturma -->
            <?php
            // Veritabanından gelen ürünlerin sayısını kontrol etme
            if (mysqli_num_rows($result) > 0) {
                // Her bir ürün için döngü oluşturma
                while ($row = mysqli_fetch_assoc($result)) {
                    // Her bir ürün için kart oluşturma
                    echo '<div class="col-md-3">';
                    echo '<div class="product-card">';
                    // Ürün resmi
                    echo '<img src="' . $row['product_url'] . '" alt="' . $row['product_name'] . '">';
                    // Ürün başlığı ve bağlantısı
                    echo '<h4 class="product-brand"><a href="urun.php?ID=' . $row["ID"] . '">' . $row["product_name"] . '</a></h4>';
                    // Ürün fiyatı
                    echo '<div class="price">' . $row["price"] . ' ₺</div>';
                    // Sepete ekleme formu
                    echo '<form method="post">';
                    echo '<input type="hidden" name="product_id" value="' . $row["ID"] . '">';
                    echo '<button type="submit" name="add_to_cart" class="card-button" onclick="showNotification()">Sepete Ekle</button>';
                    echo '</form>';
                    // Favori ikonu işlevi
                    echo '<div class="favorite-icon" data-id="' . $row["ID"] . '" onclick="toggleFavorite(' . $row["ID"] . ')">';
                    if (isset($_SESSION['favs'][$row["ID"]])) {
                        echo '<img src="assets/img/red-favs-2.png" class="favorite-heart">';
                    } else {
                        echo '<img src="assets/img/favs.png" class="favorite-heart">';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "Ürün bulunamadı.";
            }

            ?>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Chakami </p>
        </div>
    </footer>

    <script src="assets/js/bitki-meyve.js"></script>

</body>

</html>