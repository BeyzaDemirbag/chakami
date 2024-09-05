<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";
session_start();
$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Hesabım </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
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

    <div class="card">
        <div class="welcome-message">
            <!-- Oturum açılmışsa kullanıcı adını görüntüler, değilse giriş yapılması gerektiğini belirtir -->
            <?php
            if (isset($_SESSION["email"])) {
                echo "<h3>" . $_SESSION["kullanici_adi"] . " Hoşgeldiniz.</h3>";
            } else {
                echo "<p>Profil sayfasını görebilmek için giriş yapmanız gerekmektedir.</p>";
            }
            ?>
        </div>
        <!-- Oturum açılmışsa, kullanıcının e-posta adresini gösterir ve çıkış yapma bağlantısı sunar -->
        <?php
        if (isset($_SESSION["email"])) {
            echo "<div class='user-email'>" . $_SESSION["email"] . "</div>";
            echo "<a href='cikis.php' class='logout-link'>Çıkış Yap</a>";
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

    <script src="assets/js/profile.js"></script>

</body>

</html>
<?php
// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>