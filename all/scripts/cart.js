document.addEventListener("DOMContentLoaded", function () {
  const openCart = document.getElementById("open-cart");
  const closeCart = document.getElementById("close-cart");
  const miniCart = document.getElementById("mini-cart");

  if (openCart) {
    openCart.addEventListener("click", function (e) {
      e.preventDefault();
      miniCart.style.display = "block";
    });
  }

  if (closeCart) {
    closeCart.addEventListener("click", function () {
      miniCart.style.display = "none";
    });
  }

  // Fermer le panier si on clique en dehors
  window.addEventListener("click", function (e) {
    if (!miniCart.contains(e.target) && e.target !== openCart) {
      miniCart.style.display = "none";
    }
  });
});
