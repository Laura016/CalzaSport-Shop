//======================================
// MENÚ MÓVIL
//======================================

const menu = document.querySelector(".mobile-menu");
const overlay = document.querySelector(".menu-overlay");
const openBtn = document.getElementById("menuToggle");
const closeBtn = document.querySelector(".close-menu");

if (openBtn) {

    openBtn.addEventListener("click", () => {

        menu.classList.add("active");
        overlay.classList.add("active");

        document.body.style.overflow = "hidden";

    });

}

if (closeBtn) {

    closeBtn.addEventListener("click", cerrarMenu);

}

if (overlay) {

    overlay.addEventListener("click", cerrarMenu);

}

function cerrarMenu(){

    menu.classList.remove("active");
    overlay.classList.remove("active");

    document.body.style.overflow = "";

}

//======================================
// NAVBAR SCROLL
//======================================

const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {

    if(window.scrollY > 30){

        navbar.classList.add("scrolled");

    }else{

        navbar.classList.remove("scrolled");

    }

});

//======================================
// CERRAR MENÚ AL CAMBIAR TAMAÑO
//======================================

window.addEventListener("resize", ()=>{

    if(window.innerWidth > 992){

        cerrarMenu();

    }

});