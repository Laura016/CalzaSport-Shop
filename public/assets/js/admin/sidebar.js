const menu=document.querySelector(".sidebar");

const overlay=document.querySelector(".overlay");

const boton=document.getElementById("menuToggle");

if(boton){

boton.addEventListener("click",()=>{

menu.classList.toggle("active");

overlay.classList.toggle("active");

});

overlay.addEventListener("click",()=>{

menu.classList.remove("active");

overlay.classList.remove("active");

});
}