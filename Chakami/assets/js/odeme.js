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