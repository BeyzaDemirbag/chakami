<?php

include("baglanti.php"); // Bağlantı dosyasını dahil eder. Bu, veritabanı bağlantısını sağlar ve ortak işlevleri içerir.

/* Oluşturulan iki adet boş değişken, kullanıcı girişi sırasında herhangi bir doğrulama hatası meydana geldiğinde ilgili hata mesajlarını depolamak için kullanılır. Bu sayede, uygun hata mesajı gerektiğinde bu değişkenlere atanabilir ve kullanıcıya gösterilebilir. */

$emailError = "";
$passwordError = "";

// Formun gönderildiğinde eğer "giris" adında bir POST isteği varsa çalışır. 
if (isset($_POST["giris"])) {

    // Kullanıcının formu gönderdiğinde e-posta alanını doldurup doldurmadığını kontrol eder. Eğer alan boşsa hata mesajı gösterilir.

    if (empty($_POST["email"])) {
        $emailError = "Email boş bırakılamaz.";
    } else {
        $email = $_POST["email"];
    }

    // şifre

    // Kullanıcının formu gönderdiğinde şifre alanını doldurup doldurmadığını kontrol eder. Eğer alan boşsa hata mesajı gösterilir.

    if (empty($_POST["sifre"])) {
        $passwordError = "Şifre boş bırakılamaz.";
    } else {
        $sifre = $_POST["sifre"];
    }




    if (isset($email) && isset($sifre)) // Değişkenlerinin mevcut olup olmadığı isset() fonksiyonuyla kontrol edilir. Eğer her ikisi de mevcutsa, giriş işlemi başlatılır.
    {

        // Eğer veritabanında bu e-posta adresine sahip bir kullanıcı varsa, kullanıcının girdiği şifrenin hashlenmiş versiyonu ile veritabanındaki hashlenmiş şifre karşılaştırılır. Eğer şifreler eşleşiyorsa, kullanıcı oturumunu başlatılır (session oluşturulur) ve kullanıcıya profile.php sayfasına yönlendirme yapılır. 

        $secim = "SELECT * FROM kullanicilar WHERE email = '$email' ";
        $calistir = mysqli_query($baglanti, $secim);
        $kayitSayisi = mysqli_num_rows($calistir);   // sıfır ya da bir

        if ($kayitSayisi > 0) {
            $ilgiliKayit = mysqli_fetch_assoc($calistir);
            $hashliSifre = $ilgiliKayit["sifre"];

            if (password_verify($sifre, $hashliSifre)) {
                session_start();
                $_SESSION["kullanici_adi"] = $ilgiliKayit["kullaniciAdi"];
                $_SESSION["email"] = $ilgiliKayit["email"];
                header("location:profile.php");
            }

            /* Eğer şifreler eşleşmiyorsa, kullanıcı gerekli alanları doldurmadıysa veya giriş yapmak için gerekli bilgiler eksikse 
        hata mesajı gösterilir. */ else {

                echo '<div class="alert alert-danger" role="alert">
        Yanlış email adresi veya şifre.
    </div>';
            }
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Yanlış email adresi veya şifre.
    </div>';
    }





    mysqli_close($baglanti);
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Giriş Yap </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
</head>

<body>

    <img src="assets/img/beyaz-yuvarlak-logo.png" alt="Logo" class="header-image">

    <div class="container">
        <div class="card-container">
            <div class="card p-4">

                <!-- Form, bir Bootstrap bileşenini kullanarak bir kart içinde yer alır. Kart, içerisindeki içeriği düzenlemek ve gruplamak için kullanılır. Formun içeriği bir dizi form öğesini içerir. Form, kullanıcıların kayıt olabilmesi için gerekli bilgileri sağlamalarına olanak tanır. -->

                <form action="login.php" method="POST" class="form-group">

                    <!-- E-posta giriş alanını ve bu alana ilişkin hata mesajlarını içeren bir form grubunu tanımlar. Kullanıcı, geçerli bir e-posta adresi girdiğinde, form alanı standart şekilde görünür. Ancak, geçersiz bir e-posta adresi girilirse, form alanı kırmızı kenarlıkla vurgulanır ve kullanıcıya uygun bir hata mesajı gösterilir. -->

                    <div class="form-group">
                        <label for="exampleInputEmail1">Email Adresi</label>
                        <input type="email" class="form-control
                        <?php
                        if (!empty($emailError)) {
                            echo "is-invalid";
                        }
                        ?>"
                            id="exampleInputEmail1" name="email" placeholder="Email adresinizi giriniz." required>
                        <div class="invalid-feedback">
                            <?php
                            echo $emailError;
                            ?>
                        </div>
                    </div>


                    <!-- şifre giriş alanını ve bu alana ilişkin hata mesajlarını içeren bir form grubunu tanımlar. Kullanıcı, geçerli bir şifre girdiğinde, form alanı standart şekilde görünür. Ancak, geçersiz bir şifre girilirse, form alanı kırmızı kenarlıkla vurgulanır ve kullanıcıya uygun bir hata mesajı gösterilir. -->

                    <div class="form-group">
                        <label for="exampleInputPassword1">Şifre</label>
                        <div class="input-group">
                            <input type="password" class="form-control
            <?php
            if (!empty($passwordError)) {
                echo "is-invalid";
            }
            ?>"
                                id="exampleInputPassword1" name="sifre" placeholder="Şifrenizi giriniz." required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword"> <!-- Bu düğme, kullanıcının şifreyi açıp kapamasına olanak tanır. Görsel düğmenin id özelliği, JavaScript tarafından bu işlevselliği sağlamak için kullanılır. -->
                                    <img src="assets/img/eye-close.png" alt="Toggle Password" width="20" height="20">
                                </span>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            <?php
                            echo $passwordError;
                            ?>
                        </div>
                    </div>

                    <!-- Kullanıcıların giriş yapmalarını, şifrelerini sıfırlamalarını veya yeni bir hesap oluşturmalarını sağlayan bağlantılar ve butonler içerir. Bu şekilde, kullanıcılar sisteme giriş yapabilir veya yeni hesaplar oluşturabilirler. -->

                    <div class="forgot-password-link">
                        <p><a href="forgetpassword.php"> Şifremi Unuttum </a></p>
                    </div>

                    <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>

                    <div class="signup-link">
                        <p>Hesabınız yok mu? <a href="kayit.php"> Üye Ol </a></p>
                    </div>

                </form>
            </div>

            <img src="assets/img/loginJapan.png" alt="Your Image" class="custom-image"> <!-- Görselin Bootstrap tarafından sağlanan bir sınıfını içerir. Bu sınıflar, görselin görünümünü belirler ve gerektiğinde özelleştirilmiş bir stil uygulanmasını sağlar. -->
        </div>
    </div>

    <script src="assets/js/login.js"></script>

</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnXFm10wbRB" crossorigin="anonymous"></script>

</html>