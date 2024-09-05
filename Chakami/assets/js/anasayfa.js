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


// Swipe

// Ürün slider'ının hareketini sağlayan butonlar için olay dinleyiciler
const productContainers = document.querySelectorAll('.product-container');
const nextButtons = document.querySelectorAll('.nextButton');
const preButtons = document.querySelectorAll('.preButton');

// Her bir "sonraki" düğme için olay dinleyicileri ve container'ı kaydırma işlevi
for (let i = 0; i < nextButtons.length; i++) {
    const container = productContainers[i];
    const containerWidth = container.getBoundingClientRect().width;

    // Sonraki butona tıklanınca sağa kaydır
    nextButtons[i].addEventListener('click', () => {
        container.scrollLeft += containerWidth;
    });

    // Önceki butona tıklanınca sola kaydır
    preButtons[i].addEventListener('click', () => {
        container.scrollLeft -= containerWidth;
    });
}


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




// Bildirim gösterme fonksiyonu
function showNotification() {
    var notification = document.createElement('div');
    notification.classList.add('notification');
    notification.textContent = 'Ürün sepete eklendi. Sepete git';

    // Bildirimi sayfaya ekle
    document.body.appendChild(notification);

    setTimeout(function () {
        notification.remove();
    }, 3000); // Bildirimi 3 saniye sonra kaldır
}




// Favoriler ekleme silme

function toggleFavorite(productId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_favs.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    var favoriteIcon = document.querySelector('.favorite-icon[data-id="' + productId + '"] img');
                    if (response.action === "added") {
                        favoriteIcon.src = "img/red-favs-2.png";
                    } else if (response.action === "removed") {
                        favoriteIcon.src = "img/favs.png";
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
