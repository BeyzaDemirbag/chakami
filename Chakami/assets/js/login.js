
/* Fonksiyon, şifre giriş alanının tipini kontrol eder. Eğer şifre giriş alanı tipi "password" ise, metin gizlenir ve düğmenin içeriği, şifrenin açık olduğunu belirten bir görsel olarak değiştirilir. Aksi takdirde, şifre giriş alanı tipi "text" olarak değiştirilir ve düğmenin içeriği, şifrenin kapalı olduğunu belirten bir görsel olarak değiştirilir.

Bu fonksiyon, kullanıcıların şifrelerini gizli veya açık bir şekilde görmelerini sağlar. Kullanıcılar, şifrelerini gizlemek veya göstermek için ilgili görsel düğmeye tıklayabilirler. */


document.getElementById('togglePassword').addEventListener('click', function () {
    togglePasswordVisibility('exampleInputPassword1', 'togglePassword');
});

function togglePasswordVisibility(passwordInputId, toggleIconId) {
    var passwordField = document.getElementById(passwordInputId);
    var toggleIcon = document.getElementById(toggleIconId);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.innerHTML = '<img src ="img/eye-open.png" alt="Toggle Password" width="20" height="20">';
    } else {
        passwordField.type = 'password';
        toggleIcon.innerHTML = '<img src="img/eye-close.png" alt="Toggle Password" width="20" height="20">';
    }
}
