document.addEventListener("DOMContentLoaded", () => {

    const contenedor = document.getElementById("cartItems");

    if (!contenedor) return;

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    pintarCarrito();

    function pintarCarrito(){

        if(carrito.length === 0){

            contenedor.innerHTML = `

                <div class="empty-cart">

                    <i class="fa-solid fa-cart-shopping"></i>

                    <h2>Tu carrito está vacío</h2>

                    <p>Agrega productos para comenzar tu compra.</p>

                    <a href="catalogo.php" class="checkout-btn">

                        Ir al catálogo

                    </a>

                </div>

            `;

            actualizarResumen();

            return;

        }

        contenedor.innerHTML="";

        carrito.forEach((producto,index)=>{

            contenedor.innerHTML += `

                <div class="cart-item">

                    <img src="assets/img/productos/${producto.imagen}" alt="">

                    <div class="cart-info">

                        <h3>${producto.nombre}</h3>

                        <span>$${producto.precio.toLocaleString()}</span>

                    </div>

                    <div class="cart-quantity">

                        <button class="menos" data-index="${index}">−</button>

                        <span>${producto.cantidad}</span>

                        <button class="mas" data-index="${index}">+</button>

                    </div>

                    <button class="delete-item" data-index="${index}">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </div>

            `;

        });

        eventos();

        actualizarResumen();

    }

    function eventos(){

        document.querySelectorAll(".mas").forEach(btn=>{

            btn.onclick=()=>{

                carrito[btn.dataset.index].cantidad++;

                guardar();

            };

        });

        document.querySelectorAll(".menos").forEach(btn=>{

            btn.onclick=()=>{

                if(carrito[btn.dataset.index].cantidad>1){

                    carrito[btn.dataset.index].cantidad--;

                }

                guardar();

            };

        });

        document.querySelectorAll(".delete-item").forEach(btn=>{

            btn.onclick=()=>{

                carrito.splice(btn.dataset.index,1);

                guardar();

            };

        });

    }

    function guardar(){

        localStorage.setItem("carrito",JSON.stringify(carrito));

        pintarCarrito();

    }

    function actualizarResumen(){

        let subtotal=0;

        carrito.forEach(p=>{

            subtotal += p.precio*p.cantidad;

        });

        document.getElementById("subtotal").textContent=

            "$"+subtotal.toLocaleString();

        document.getElementById("total").textContent=

            "$"+subtotal.toLocaleString();

    }

});