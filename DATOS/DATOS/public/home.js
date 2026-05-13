document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.querySelector('.search-input');
    const botonesNav = document.querySelectorAll('.nav-item');
    const btnExplorar = botonesNav[1];

    if (btnExplorar && buscador) {
        btnExplorar.addEventListener('click', function(e) {
            e.preventDefault();

            buscador.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });

            setTimeout(() => {
                buscador.focus();
                
                buscador.style.outline = "3px solid #634832";
                buscador.style.transition = "outline 0.3s ease";
                
                setTimeout(() => {
                    buscador.style.outline = "none";
                }, 1000);
            }, 600);
        });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    
    const btnGuardados = document.getElementById('btn-guardados');
    const modalFavoritos = document.getElementById('modal-favoritos');
    const btnCerrarModal = document.querySelector('.close-button');

    if (btnGuardados && modalFavoritos) {
        btnGuardados.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("Abriendo ventana de favoritos...");
            modalFavoritos.style.display = "block";
        });

        if (btnCerrarModal) {
            btnCerrarModal.onclick = function() {
                modalFavoritos.style.display = "none";
            }
        }

        window.addEventListener('click', function(event) {
            if (event.target == modalFavoritos) {
                modalFavoritos.style.display = "none";
            }
        });
    } else {
        console.error("No se encontró el botón o el modal en el HTML");
    }
});


const btnReportar = document.getElementById('btn-reportar');
const modalReportar = document.getElementById('modal-reportar');
const closeReportar = document.getElementById('close-reportar');

if (btnReportar && modalReportar) {
    btnReportar.addEventListener('click', function(e) {
        e.preventDefault();
        modalReportar.style.display = "block";
    });

    if (closeReportar) {
        closeReportar.onclick = function() {
            modalReportar.style.display = "none";
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalReportar) {
            modalReportar.style.display = "none";
        }
    });
}
