<?php

$host = "localhost";
$kullanici_adi = "root";
$sifre = "";
$vt = "uyelikler";

$baglanti = mysqli_connect($host, $kullanici_adi, $sifre, $vt);
mysqli_set_charset($baglanti, "UTF8");


?>