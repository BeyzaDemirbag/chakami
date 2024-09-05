<?php

include("baglanti.php");  // Bağlantı dosyasını dahil eder. Bu, veritabanı bağlantısını sağlar ve ortak işlevleri içerir.

$usernameError = "";
$emailError = "";
$passwordError = "";
$password2Error = "";

/* Oluşturulan dört adet boş değişken, kullanıcı kaydı sırasında herhangi bir doğrulama hatası meydana geldiğinde ilgili hata mesajlarını depolamak için kullanılır. Bu sayede, uygun hata mesajı gerektiğinde bu değişkenlere atanabilir ve kullanıcıya gösterilebilir. */

if (isset($_POST["kaydet"])) {

    if (empty($_POST["kullaniciAdi"])) {
        $usernameError = "Kullanıcı adı boş bırakılamaz.";
    } else if (strlen($_POST["kullaniciAdi"]) < 3) {
        $usernameError = "Kullanıcı adı üç karakterden kısa olamaz.";
    } else if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $_POST["kullaniciAdi"])) {
        $usernameError = "Kullanıcı adı geçersiz. Özel karakterler kullanılamaz.";
    } else {
        $username = $_POST["kullaniciAdi"];
    }

    /* Kullanıcı adı doğruluğunu kontrol eder.
Kullanıcı adı boş bırakıldığında, 3 karakterden az girildiğinde ve özel karakterler
kullanıldığında hata mesajı gösterir.
Kurallara uygun kullanıcı kabul edilir. */


    if (empty($_POST["email"])) {
        $emailError = "Email boş bırakılamaz.";
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) { // e-posta adresinin geçerli bir formatta olup olmadığını kontrol eder.
        $emailError = "Email formatınız hatalı.";
    } else {
        $email = $_POST["email"];
    }

    /* Kullanıcı tarafından sağlanan e-posta adresinin geçerliliğini kontrol eder ve uygun bir e-posta adresi sağlanırsa,
işlemi devam ettirir. Uygun olmayan bir e-posta adresi için hata mesajlarını görüntüler. */

    // şifre

    if (empty($_POST["sifre"])) {
        $passwordError = "Şifre boş bırakılamaz.";
    } else if (strlen($_POST["sifre"]) < 5) {
        $passwordError = "Şifre beş karakterden kısa olamaz.";
    } else {
        $sifre = password_hash($_POST["sifre"], PASSWORD_DEFAULT);
    }

    /* Şifrenin boş bırakılıp bırakılmadığını ve en az 5 karakter uzunluğunda olup olmadığını kontrol eder. Eğer boşsa veya 5 karakterden kısa ise, hata mesajı gösterir.
Şifre geçerliyse şifreyi hash'ler ve $sifre değişkenine atar. */

    //şifre tekrar

    if (empty($_POST["sifreTekrar"])) {
        $password2Error = "Lütfen şifrenizi tekrar giriniz.";
    } else if ($_POST["sifre"] != $_POST["sifreTekrar"]) {
        $password2Error = "Şifre uyumsuzluğu bulunmakta.";
    } else {
        $sifreTekrar = $_POST["sifreTekrar"];
    }

    /* Kullanıcı tarafından sağlanan şifrenin doğruluğunu kontrol eder ve uygun bir şifre sağlanırsa işlemi devam ettirir,
uygun olmayan bir şifre için hata mesajlarını görüntüler. */






    if (isset($username) && isset($email) && isset($sifre) && isset($sifreTekrar)) // Kullanıcı adı, e-posta ve şifrenin tanımlı olup olmadığını kontrol eder. Kullanıcı tarafından geçerli bilgiler sağlanmışsa, işlemi devam ettirir.
    {



        $ekle = "INSERT INTO kullanicilar (kullanici_adi, email, sifre) VALUES ('$username', '$email', '$sifre')";
        // Kullanıcı bilgilerini "kullanicilar" adlı tabloya eklemek için SQL sorgusunu oluşturur.

        $calistirEkle = mysqli_query($baglanti, $ekle);

        if ($calistirEkle) // Oluşturulan SQL sorgusunu çalıştırır ve kullanıcı bilgilerini veritabanına ekler.
        {
            echo '<div class="alert alert-success" role="alert">
        Kayıt işleminiz tamamlanmıştır.
      </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Kayıt gerçekleştirilemedi.
        </div>';
        }

        /* Kullanıcı tarafından sağlanan geçerli bilgilerle bir kayıt işlemi gerçekleştirir ve işlemin sonucuna göre 
    kullanıcıya uygun bir geri bildirim sağlar. */

        mysqli_close($baglanti);
    }
}

?>



<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Üye Ol </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/kayit.css">
</head>

<body>

    <img src="assets/img/beyaz-yuvarlak-logo.png" alt="Logo" class="header-image">

    <div class="container">
        <div class="card-container">
            <div class="card p-4">

                <!-- Form, bir Bootstrap bileşenini kullanarak bir kart içinde yer alır. Kart, içerisindeki içeriği düzenlemek ve gruplamak için kullanılır. Formun içeriği bir dizi form öğesini içerir. Form, kullanıcıların kayıt olabilmesi için gerekli bilgileri sağlamalarına olanak tanır. -->

                <form action="kayit.php" method="POST" class="form-group">

                    <div class="form-group">
                        <label for="exampleUsername">Kullanıcı Adı</label>
                        <input type="text" class="form-control
                        <?php
                        if (!empty($usernameError)) {
                            echo "is-invalid";
                        }
                        ?>"
                            id="exampleUsername" name="kullaniciAdi" placeholder="Kullanıcı adınızı giriniz." required>
                        <div class="invalid-feedback">
                            <?php
                            echo $usernameError;
                            ?>
                        </div>
                    </div>

                    <!-- Giriş alanının etiketi, kullanıcıya ne tür bir bilgi girmesi gerektiğini belirtir. Giriş alanı, gerektiğinde hata mesajı ile birlikte kırmızı kenarlıkla vurgulanabilir. Eğer kullanıcı adı geçerli değilse, bir hata mesajı görüntülenir. -->

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

                    <!-- E-posta giriş alanını ve bu alana ilişkin hata mesajlarını içeren bir form grubunu tanımlar. Kullanıcı, geçerli bir e-posta adresi girdiğinde, form alanı standart şekilde görünür. Ancak, geçersiz bir e-posta adresi girilirse, form alanı kırmızı kenarlıkla vurgulanır ve kullanıcıya uygun bir hata mesajı gösterilir. -->

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

                    <!-- şifre giriş alanını ve bu alana ilişkin hata mesajlarını içeren bir form grubunu tanımlar. Kullanıcı, geçerli bir şifre girdiğinde, form alanı standart şekilde görünür. Ancak, geçersiz bir şifre girilirse, form alanı kırmızı kenarlıkla vurgulanır ve kullanıcıya uygun bir hata mesajı gösterilir. -->


                    <!-- şifre tekrar giriş alanını ve bu alana ilişkin hata mesajlarını içeren bir form grubunu tanımlar. Kullanıcı, geçerli bir şifre tekrarı girdiğinde, form alanı standart şekilde görünür. Ancak, geçersiz bir şifre tekrarı girilirse, form alanı kırmızı kenarlıkla vurgulanır ve kullanıcıya uygun bir hata mesajı gösterilir. -->
                    <div class="form-group">
                        <label for="exampleInputPassword2">Şifre Tekrar</label>
                        <div class="input-group">
                            <input type="password" class="form-control
            <?php
            if (!empty($password2Error)) {
                echo "is-invalid";
            }
            ?>"
                                id="exampleInputPassword2" name="sifreTekrar" placeholder="Şifrenizi tekrar giriniz." required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword2"> <!-- Bu düğme, kullanıcının şifreyi açıp kapamasına olanak tanır. Görsel düğmenin id özelliği, JavaScript tarafından bu işlevselliği sağlamak için kullanılır. -->
                                    <img src="assets/img/eye-close.png" alt="Toggle Password" width="20" height="20">
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="login-link">
                        <p>Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p> <!-- Kullanıcıya, zaten bir hesabı varsa giriş yapabileceği bir link veya formu göndermek için "Kayıt Ol" düğmesi sunulur. -->
                    </div>

                    <button type="submit" name="kaydet" class="btn btn-primary">Kayıt Ol</button>

                </form>
            </div>

            <img src="assets/img/chinese4.png" alt="Your Image" class="custom-image"> <!-- görselin Bootstrap tarafından sağlanan bir sınıfını içerir. Bu sınıflar, görselin görünümünü belirler ve gerektiğinde özelleştirilmiş bir stil uygulanmasını sağlar. -->
        </div>
    </div>

    <script src="assets/js/kayit.js"></script>

</body>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnXFm10wbRB" crossorigin="anonymous"></script>

</html>