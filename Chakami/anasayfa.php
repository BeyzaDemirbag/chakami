<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost"; // Veritabanı sunucusu
$kullanici_adi = "root"; // Veritabanı kullanıcı adı
$sifre = ""; // Veritabanı şifresi
$vt = "urunler"; // Kullanılacak veritabanı

// Oturumu başlat
session_start();

// Veritabanı bağlantısını oluştur
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);

// Bağlantı karakter setini UTF-8 olarak ayarla
mysqli_set_charset($baglanti, "UTF8");

// Ürünleri getirmek için SQL sorgusu
$sql = "SELECT ID, product_url, product_name, product_type, price FROM urun_liste LIMIT 8";

// Sorguyu çalıştır ve sonucu al
$result = mysqli_query($baglanti, $sql);

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
        echo "Ürün favorilere eklendi.";
    }
}

?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Chakami </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/anasayfa.css">
</head>

<body>
    <!-- Navigasyon çubuğu -->
    <nav class="navbar">
        <!-- Logo -->
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
            <!-- Favoriler linki -->
            <a href="favoriler.php"><img src="assets/img/favs.png" alt="Favoriler"></a>
            <!-- Profil popup -->
            <div class="profile-popup">
                <!-- Profil resmi ve linki -->
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
            <!-- Sepet linki -->
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

    <div class="container best-sellers">
        <!-- Slider container -->
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="assets/img/slide0.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5> Japon Çay Seremonisi </h5>
                        <p> Çayın ilk kullanılmaya başlandığı ve çay törenlerinin ilk yapıldığı yer Çin olsa da buna sanatsal bir form kazandırıp dünyaya tanıtan ülke Japonya’dır. </p>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="assets/img/Camellia sinensis by Walter Müller 1887.jpg.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5> Camellia Sinensis </h5>
                        <p> Tüm çaylar aynı bitkiden elde edilir. Bu çaylar arasındaki fark oksidasyon sürelerinden kaynaklanır.
                            Örneğin, siyah çay bu bitkinin tamamen oksitlenmiş halidir. Beyaz çay ise neredeyse hiç oksitlenmemiştir. </p>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="assets/img/slide3.png" class="d-block w-100" alt="Çay Okulları">
                    <div class="carousel-caption d-none d-md-block">
                        <h5> Çay Hazırlama Yaklaşımları </h5>
                        <p> Özel çay okulları açıldığından ve her bir okulun bugün hala takip edilen kendi tercih ettikleri yaklaşımlara sahip olduğundan, çayların miktarları ve hazırlık yöntemleri değişmektedir. </p>
                    </div>
                </div>
            </div>
            <!-- Slider kontrolleri -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <section class="product">
            <h2 class="product-category">En Çok Tercih Edilen Ürünler</h2>
            <!-- Slider kontrol butonları -->
            <button class="preButton"> <img src="assets/img/left-arrow.png"></button>
            <button class="nextButton"> <img src="assets/img/right-arrow.png"></button>
            <!-- Ürün kartları container'ı -->
            <div class="product-container">
                <?php
                // PHP kodu ile ürün kartlarını oluşturma
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card">';
                        echo '<div class="product-img">';
                        echo '<img src="' . $row["product_url"] . '" class="product-thumb">';
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
                        echo '<div class="product-info">';
                        // Ürün ismine bir link ekleme, linkin hedefi urun.php sayfası
                        echo '<h4 class="product-brand"><a href="urun.php?ID=' . $row["ID"] . '">' . $row["product_name"] . '</a></h4>';
                        echo '<p class="product-short-description">' . $row["product_type"] . '</p>';
                        echo '<div class="price">' . $row["price"] . ' ₺</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "Ürün bulunamadı.";
                }
                ?>
            </div>
        </section>

        <p class="baslik2">MÜKEMMEL DENEYİM İÇİN</p>
        <!-- Demleme tablosu -->
        <div class="brewing-table">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Çay Çeşidi</th>
                        <th scope="col">Demleme Sıcaklığı</th>
                        <th scope="col">Fincan Başına Miktar</th>
                        <th scope="col">Demleme Süresi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Beyaz Çay</td>
                        <td>75-80°C</td>
                        <td>1 çay kaşığı (2 gram)</td>
                        <td>2-3 dk.</td>
                    </tr>
                    <tr>
                        <td>Yeşil Çay</td>
                        <td>80°C</td>
                        <td>1 çay kaşığı (2 gram)</td>
                        <td>2-3 dk.</td>
                    </tr>
                    <tr>
                        <td>Oolong</td>
                        <td>90°C</td>
                        <td>1 çay kaşığı (2 gram)</td>
                        <td>3-4 dk.</td>
                    </tr>
                    <tr>
                        <td>Siyah Çay</td>
                        <td>100°C</td>
                        <td>1 çay kaşığı (2 gram)</td>
                        <td>3-5 dk.</td>
                    </tr>
                    <tr>
                        <td>Bitki & Meyve</td>
                        <td>100°C</td>
                        <td>1 çay kaşığı (2 gram)</td>
                        <td>5-8 dk.</td>
                    </tr>
                </tbody>
            </table>
            <p>400 ml su için 4 gram çay yaprağı yeterli olacaktır.</p>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Chakami </p>
        </div>
    </footer>

    <script src="assets/js/anasayfa.js"></script>

</body>

</html>
<?php
// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>