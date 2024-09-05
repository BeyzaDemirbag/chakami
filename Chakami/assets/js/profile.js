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

// Profil popup'ının açılma ve kapanma davranışını yöneten olay dinleyiciler
document.addEventListener("DOMContentLoaded", function () {
    var profilePopup = document.querySelector(".profile-popup");
    var profilePopupContent = document.querySelector(".profile-popup-content");
    var isMouseOverPopup = false;

    // Profil kutusuna fare geldiğinde popup'ı aç
    profilePopup.addEventListener("mouseenter", function () {
        profilePopupContent.style.display = "block";
        isMouseOverPopup = true;
    });

    // Profil kutusundan fare ayrıldığında popup'ı kapat
    profilePopup.addEventListener("mouseleave", function () {
        isMouseOverPopup = false;
        // Bir sonraki işlemin tetiklenmesi için bir miktar gecikme 
        setTimeout(function () {
            if (!isMouseOverPopup) {
                profilePopupContent.style.display = "none";
            }
        }, 200); // 200 milisaniye gecikme
    });

    // Popup içine fare geldiğinde popup'ı açık tut
    profilePopupContent.addEventListener("mouseenter", function () {
        isMouseOverPopup = true;
    });

    // Popup içinden fare ayrıldığında popup'ı kapat
    profilePopupContent.addEventListener("mouseleave", function () {
        isMouseOverPopup = false;
        // Bir sonraki işlemin tetiklenmesi için bir miktar gecikme
        setTimeout(function () {
            if (!isMouseOverPopup) {
                profilePopupContent.style.display = "none";
            }
        }, 200); // 200 milisaniye gecikme
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

