const menu = document.querySelector(".mobile-menu");

const overlay = document.querySelector(".menu-overlay");

const openBtn = document.getElementById("menuToggle");

const closeBtn = document.querySelector(".close-menu");

openBtn.addEventListener("click", () => {

    menu.classList.add("active");

    overlay.classList.add("active");

});

closeBtn.addEventListener("click", cerrarMenu);

overlay.addEventListener("click", cerrarMenu);

function cerrarMenu(){

    menu.classList.remove("active");

    overlay.classList.remove("active");

}