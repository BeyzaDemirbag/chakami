<?php

$con = new PDO("mysql:host=localhost;dbname=urunler", 'root', '');


if (isset($_POST["query"])) {
    $str = $_POST["query"];
    $sth = $con->prepare("SELECT * FROM `urun_liste` WHERE product_name LIKE :str");
    $sth->bindValue(':str', '%' . $str . '%');
    $sth->setFetchMode(PDO::FETCH_OBJ);
    $sth->execute();

    if ($sth->rowCount() > 0) {
        while ($row = $sth->fetch()) {
            echo "<div class='search-product-item'>
                    <a href='urun.php?ID={$row->ID}' class='search-product-link'>
                        <img src='{$row->product_url}' alt='{$row->product_name}' class='search-product-image'>
                        <span class='search-product-name'>{$row->product_name}</span>
                    </a>
                  </div>";
        }
    } else {
        echo "Ürün bulunamadı.";
    }
}
?>
