<?php

include("baglanti.php");  // // Bağlantı dosyasını dahil eder. Bu, veritabanı bağlantısını sağlar ve ortak işlevleri içerir.

$emailError = "";
// /* Oluşturulan boş değişken, herhangi bir doğrulama hatası meydana geldiğinde ilgili hata mesajlarını depolamak için kullanılır. Bu sayede, uygun hata mesajı gerektiğinde bu değişkenle atanabilir ve kullanıcıya gösterilebilir. */


if (isset($_POST["giris"])) {

    // Kullanıcının formu gönderdiğinde e-posta alanını doldurup doldurmadığını kontrol eder. Eğer alan boşsa hata mesajı gösterilir.

    if (empty($_POST["email"])) {
        $emailError = "Email boş bırakılamaz.";
    } else {
        $email = $_POST["email"];
    }

    mysqli_close($baglanti);
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Şifre Yenileme </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/forgetpassword.css">
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

                    <!-- kullanıcının e-posta adresine bir doğrulama kodu göndermek için formu göndermesini sağlar. -->

                    <button type="submit" name="sifreYenile" class="btn btn-primary mt-4"> Doğrulama Kodu Gönder </button>
                </form>
            </div>

            <img src="assets/img/forgetpassword-chinese.png" alt=" Forget Password Image" class="custom-image">
            <!-- Görselin Bootstrap tarafından sağlanan bir sınıfını içerir. Bu sınıflar, görselin görünümünü belirler ve gerektiğinde özelleştirilmiş bir stil uygulanmasını sağlar. -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnXFm10wbRB" crossorigin="anonymous"></script>

</html>