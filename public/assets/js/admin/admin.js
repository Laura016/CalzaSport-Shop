/*
=====================================
CalzaSport - Admin
Archivo para funciones generales
del panel administrativo.
=====================================
*/

console.log("Panel Administrativo iniciado");

document.addEventListener('DOMContentLoaded', function () {

    const dropdown = document.querySelector('.sidebar-dropdown');
    const toggle = document.querySelector('.sidebar-dropdown-toggle');

    if (!dropdown || !toggle) {
        return;
    }

    toggle.addEventListener('click', function () {
        dropdown.classList.toggle('active');
    });

});





