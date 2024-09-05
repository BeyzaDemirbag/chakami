<?php
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

session_start();

// Kullanıcı oturumunu kontrol et
if (isset($_SESSION["email"])) {
    // Kullanıcı oturumu varsa, ödeme sayfasına yönlendir
    $redirect_url = "favoriler.php";
} else {
    // Kullanıcı oturumu yoksa, giriş sayfasına yönlendir
    $redirect_url = "login.php";
}

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

$fav_items = $_SESSION['favs'] ?? [];

?>



<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Favorilerim </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/favoriler.css">
</head>

<body>
    <nav class="navbar">

        </div>
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
        <h2>Favorilerim</h2>
        <div class="row">
            <?php
            // Kullanıcı giriş yapmışsa favori ürünleri göster
            if (isset($_SESSION["email"])) {
                // Eğer favori ürün yoksa mesaj göster
                if (empty($fav_items)) {
                    echo '<div class="col-12 text-center">Favorilerinizde ürün bulunmamaktadır.</div>';
                } else {
                    foreach ($fav_items as $product_id => $is_fav) {
                        $sql = "SELECT * FROM urun_liste WHERE ID = $product_id";
                        $result = mysqli_query($baglanti, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="col-md-3 mb-4">'; // Her satırda 4 ürün
                                echo '<div class="product-card">';
                                // Ürün resmi
                                echo '<img src="' . $row['product_url'] . '" alt="' . $row['product_name'] . '" class="img-fluid">';
                                // Ürün başlığı ve bağlantısı
                                echo '<h4 class="product-brand"><a href="urun.php?ID=' . $row["ID"] . '">' . $row["product_name"] . '</a></h4>';
                                echo '<div class="price">' . $row["price"] . ' ₺</div>';
                                echo '<div class="button-container" style="display: flex; align-items: center;">';
                                // Sepete ekleme formu
                                echo '<form method="post">';
                                echo '<input type="hidden" name="product_id" value="' . $row["ID"] . '">';
                                echo '<button type="submit" name="add_to_cart" class="sepet-button" onclick="showNotification()"><img src="img/basket.png" alt="Sepet"></button>';
                                echo '</form>';
                                // Favorilerden kaldır
                                echo '<div class="favorite-icon" data-id="' . $row["ID"] . '" onclick="toggleFavorite(' . $row["ID"] . ', true)">';
                                echo '<button class="remove"><img src="assets/img/red-favs-2.png" alt="Sil"></button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>'; // product-card kapanışı
                                echo '</div>'; // col-md-3 kapanışı
                            }
                        }
                    }
                }
            } else {
                // Kullanıcı giriş yapmadıysa favorileri görmesi için giriş yapma mesajı göster
                echo "<div class='col-12 text-center'>Favori ürünlerinizi görebilmek için giriş yapmanız gerekmektedir.</div>";
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Chakami </p>
        </div>
    </footer>

    <script src="assets/js/favoriler.js"></script>

</body>


</html>