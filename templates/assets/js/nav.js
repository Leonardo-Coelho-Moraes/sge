const mobileMenu = document.querySelector(".mobile-menu");
const aside = document.querySelector(".aside");

function toggleActive() {
    aside.classList.toggle("active");
    mobileMenu.classList.toggle("active");
}

mobileMenu.addEventListener("click", toggleActive);
window.addEventListener("load", checkScreenWidth);
window.addEventListener("resize", checkScreenWidth);

