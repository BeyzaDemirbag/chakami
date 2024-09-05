//Search bar'ın dışına tıklanması

// Dokümanın herhangi bir yerine tıklandığında
document.addEventListener("click", function (event) {
    // Arama inputu ve sonuç kutusunu al
    var searchBar = document.querySelector(".search-bar");
    var resultBox = document.getElementById("result");

    // Tıklamanın arama çubuğu veya sonuç kutusu içinde olup olmadığını kontrol et
    var isClickInsideSearch = searchBar.contains(event.target) || resultBox.contains(event.target);

    // Eğer tıklama bu alanlar dışında ise sonuç kutusunu gizle
    if (!isClickInsideSearch) {
        resultBox.style.display = "none";
    }
    // Arama inputuna her odaklanıldığında sonuç kutusunu tekrar görünür yap
    var searchInput = document.getElementById("search");
    searchInput.addEventListener("focus", function () {
        // Eğer arama sonuçları varsa sonuç kutusunu tekrar göster
        if (searchInput.value !== "") {
            document.getElementById("result").style.display = "block";
        }
    });
});


// Profile kutusunun açık kalması

document.addEventListener("DOMContentLoaded", function () {
    var profilePopup = document.querySelector(".profile-popup");
    var profilePopupContent = document.querySelector(".profile-popup-content");

    // Profil ikonuna tıklandığında aç/kapat
    profilePopup.addEventListener("click", function () {
        profilePopupContent.style.display = "block";
    });

    // Dokümanın herhangi bir yerine tıklandığında
    document.addEventListener("click", function (event) {
        // Profil kutusu içinde mi kontrol et
        var isClickInsidePopup = profilePopup.contains(event.target);
        // Eğer tıklama profil kutusu içinde değilse, kutuyu kapat
        if (!isClickInsidePopup) {
            profilePopupContent.style.display = "none";
        }
    });

    // Profil kutusu içinde bir yere tıklandığında, kutunun kapanmasını engelle
    profilePopupContent.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});


// Search bar

function liveSearch() {
    var searchQuery = document.getElementById("search").value;

    if (searchQuery != "") {
        $.ajax({
            url: "search.php",
            method: "POST",
            data: { query: searchQuery },
            success: function (data) {
                document.getElementById("result").innerHTML = data;
            }
        });
    } else {
        document.getElementById("result").innerHTML = "";
    }
}


// Favoriler ekleme silme

function toggleFavorite(productId, removeElement = false) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_favs.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    if (removeElement && response.action === "removed") {
                        var productElement = document.querySelector('.favorite-icon[data-id="' + productId + '"]').closest('.col-md-3');
                        if (productElement) {
                            productElement.remove();
                        }
                    } else {
                        var favoriteIcon = document.querySelector('.favorite-icon[data-id="' + productId + '"] img');
                        if (response.action === "added") {
                            favoriteIcon.src = "img/red-favs-2.png";
                        } else if (response.action === "removed") {
                            favoriteIcon.src = "img/favs.png";
                        }
                    }
                } else {
                    console.error(response.message);
                }
            } catch (e) {
                console.error("Yanıt ayrıştırma hatası: " + e.message);
            }
        }
    };

    xhr.send("action=toggle&product_id=" + productId);
}
