document.addEventListener("DOMContentLoaded", function () {
    const passwordField = document.getElementById("password");
    const toggleButton = document.getElementById("toggle-password");
  
    toggleButton.addEventListener("click", function () {
      if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.textContent = "🙈 Masquer le mot de passe";
      } else {
        passwordField.type = "password";
        toggleButton.textContent = "👁️ Afficher le mot de passe";
      }
    });
  });