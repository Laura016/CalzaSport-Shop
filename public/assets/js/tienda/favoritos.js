document.addEventListener("DOMContentLoaded", () => {

    const botones = document.querySelectorAll(".favorite");

    let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];

    botones.forEach(boton => {

        const id = boton.dataset.id;

        const icono = boton.querySelector("i");

        if (favoritos.some(f => f.id === id)) {

            icono.classList.remove("fa-regular");
            icono.classList.add("fa-solid");

            boton.classList.add("active");

        }

        boton.addEventListener("click", () => {

            const existe = favoritos.find(f => f.id === id);

            if (existe) {

                favoritos = favoritos.filter(f => f.id !== id);

                icono.classList.remove("fa-solid");
                icono.classList.add("fa-regular");

                boton.classList.remove("active");

            } else {

                favoritos.push({

                    id: id,
                    nombre: boton.dataset.nombre,
                    precio: boton.dataset.precio,
                    imagen: boton.dataset.imagen

                });

                icono.classList.remove("fa-regular");
                icono.classList.add("fa-solid");

                boton.classList.add("active");

            }

            localStorage.setItem("favoritos", JSON.stringify(favoritos));

        });

    });

});