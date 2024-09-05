<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

// Veritabanına bağlanma
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");


session_start();

// Ürün ID'sini URL'den alma
$ID = $_GET['ID'];

// Veritabanından ilgili ürünün bilgilerini alma
$sql = "SELECT * FROM urun_liste WHERE ID = $ID";
$result = mysqli_query($baglanti, $sql);

// Ürün bilgilerini tanımlamak için değişkenler
$product_url = "";
$product_name = "";
$product_type = "";
$product_expl = "";
$price = "";

if ($result) {
    // Veritabanından gelen verileri döngüyle alma ve ekrana yazdırma
    while ($row = mysqli_fetch_assoc($result)) {

        // Veritabanından gelen ürün bilgilerini alma
        $product_url = $row['product_url'];
        $product_name = $row['product_name'];
        $product_type = $row['product_type'];
        $product_expl = $row['product_expl'];
        $price = $row['price'];
    }
}

// Sepete ürün eklemek için işlem
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    // Eğer ürün zaten sepete eklenmişse, adetini artır
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // Yeni bir ürün ekle
        $_SESSION['cart'][$product_id] = array('quantity' => 1);
    }
    // Kullanıcıya ürünün sepete eklendiğine dair bildirim göster
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
    }
}

?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Ürün </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/urun.css">
</head>

<body>
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
            </div>
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

    <main>
        <div class="main-container">
            <!-- Ürün detaylarını içeren bölüm -->
            <div class="product-details">

                <div class="product-image">
                    <!-- Ürün resmi için değişken -->
                    <img src="<?php echo $product_url; ?>" alt="Ürün Resmi">

                    <!-- Favori ikonu -->
                    <div class="favorite-icon" data-id="<?php echo $ID; ?>" onclick="toggleFavorite(<?php echo $ID; ?>)">
                        <?php if (isset($_SESSION['favs'][$ID])): ?>
                            <img src="assets/img/red-favs-2.png" class="favorite-heart">
                        <?php else: ?>
                            <img src="assets/img/favs.png" class="favorite-heart">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ürün bilgilerinin bulunduğu alan -->
                <div class="product-info">
                    <!-- Ürün adı -->
                    <h1><?php echo $product_name; ?></h1>
                    <!-- Ürün açıklaması -->
                    <p><?php echo $product_expl; ?></p>
                    <!-- Ürün fiyatı -->
                    <p><?php echo $price; ?> ₺</p>
                    <!-- Sepete ekle butonu ve form -->
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?php echo $ID; ?>">
                        <button type="submit" name="add_to_cart" class="add-to-cart" onclick="showNotification()">Sepete Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </main>


    <div class="container">
        <h1 class="text-center my-4">Benzer Ürünler</h1>
        <div class="row">

            <?php
            // Benzer ürünlerin sayısı
            $benzer_urun_sayisi = 4;

            // Ana ürün ID'si
            $ana_urun_id = $ID;

            // Benzer ürünlerin sorgusu
            $benzer_urun_sql = "SELECT * FROM urun_liste WHERE product_type = '$product_type' AND ID != $ana_urun_id ORDER BY RAND() LIMIT $benzer_urun_sayisi";

            // Sorguyu çalıştırma
            $benzer_urun_result = mysqli_query($baglanti, $benzer_urun_sql);

            // Benzer ürünlerin HTML içeriği
            if (mysqli_num_rows($benzer_urun_result) > 0) {
                while ($benzer_urun_row = mysqli_fetch_assoc($benzer_urun_result)) {
                    echo '<div class="col-md-3">';
                    echo '<div class="similar-product-card">';
                    echo '<a href="urun.php?ID=' . $benzer_urun_row['ID'] . '" ">';
                    echo '<img src="' . $benzer_urun_row['product_url'] . '" alt="' . $benzer_urun_row['product_name'] . '">';
                    echo '<h3>' . $benzer_urun_row['product_name'] . '</h3>';
                    echo '</a>';
                    echo '<p>' . $benzer_urun_row["price"] . ' ₺</p>';
                    // Benzer ürünü sepete eklemek için form
                    echo '<form method="post">';
                    echo '<input type="hidden" name="product_id" value="' . $benzer_urun_row['ID'] . '">';
                    echo '<button type="submit" name="add_to_cart" class="card-button">Sepete Ekle</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>

        </div>
    </div>

    </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Chakami </p>
        </div>
    </footer>

    <script src="assets/js/urun.js"></script>

</body>

</html>
<?php
// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>