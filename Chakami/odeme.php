<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "urunler";

$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");

?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Satın Al </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/odeme.css">
</head>

<body>
    <!-- Navigasyon çubuğu -->
    <nav class="navbar">

    <div class="logo">
            <a href="sepet.php"><img src="assets/img/duz-yesil-logo.png" alt="Logo"></a>
        </div>
    </nav>
       
    <div class="container">
        <!-- Ödeme formu -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="payment-form">
                    <h2>Satın Al</h2>
                    <form action="odeme.php" method="post">
                        <!-- Kart sahibinin adı soyadı giriş alanı -->
                        <div class="form-group">
                            <label for="card_holder_name">Kart Sahibinin Adı Soyadı</label>
                            <input type="text" id="card_holder_name" name="card_holder_name" class="form-control" placeholder="Adınızı ve soyadınızı girin">
                        </div>
                        <!-- Kart numarası giriş alanı -->
                        <div class="form-group">
                            <label for="card_number"> Kart Numarası</label>
                            <input type="text" id="card_number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                        </div>
                        <!-- Son kullanma tarihi ve CVV giriş alanları -->
                        <div class="form-group row">
                            <div class="col">
                                <label for="expiry_date">Son Kullanma Tarihi</label>
                                <input type="text" id="expiry_date" name="expiry_date" class="form-control" placeholder="AA/YY">
                            </div>
                            <div class="col">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123">
                            </div>
                        </div>
                        <!-- Ödemeyi tamamla butonu -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-pay">Ödemeyi Tamamla</button>
                        </div>

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

<script src="assets/js/odeme.js"></script>

</body>

</html>
<?php
// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>