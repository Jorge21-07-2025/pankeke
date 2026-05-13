let allPets = [];

async function loadPets() {
    try {
        const response = await fetch('/mascotas/json');
        const data = await response.json();
        allPets = data.pets;
        renderPets(allPets);
    } catch (error) {
        console.error('Error loading pets:', error);
        showError();
    }
}

function renderPets(pets) {
    const petsGrid = document.getElementById('petsGrid');
    if (!petsGrid) return;

    if (pets.length === 0) {
        petsGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--text-medium); padding: 40px;">No se encontraron mascotas</p>';
        return;
    }

    petsGrid.innerHTML = pets.map(pet => `
        <div class="pet-card" data-pet-id="${pet.id}" onclick="viewPetDetail(${pet.id})">
            <div class="pet-image-container" style="background: linear-gradient(135deg, ${pet.color || '#ffeaa7'}, ${pet.color || '#fdcb6e'}dd)">
                ${pet.image ? `
                    <img src="${pet.image}" alt="${pet.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                    <span class="pet-emoji" style="display: none; font-size: 48px; width: 100%; height: 100%; align-items: center; justify-content: center;">${pet.emoji || '🐾'}</span>
                ` : `
                    <span class="pet-emoji" style="font-size: 48px;">${pet.emoji || '🐾'}</span>
                `}
            </div>
            <div class="pet-info">
                <h3 class="pet-name">${pet.name}</h3>
                <p class="pet-details">${pet.age} ${pet.age_unit} · ${pet.city || 'Sin ubicación'}</p>
                <span class="badge badge-success">${pet.status}</span>
            </div>
        </div>
    `).join('');
}

function showError() {
    const petsGrid = document.getElementById('petsGrid');
    if (petsGrid) {
        petsGrid.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                <p style="font-size: 48px; margin-bottom: 16px;">😕</p>
                <p style="color: var(--text-medium);">Error al cargar las mascotas</p>
            </div>
        `;
    }
}

function viewPetDetail(petId) {
    window.location.href = `/mascota/${petId}`;
}

function filterPets(searchTerm) {
    if (!searchTerm) {
        renderPets(allPets);
        return;
    }
    const filtered = allPets.filter(pet =>
        pet.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        (pet.breed && pet.breed.toLowerCase().includes(searchTerm.toLowerCase())) ||
        (pet.city && pet.city.toLowerCase().includes(searchTerm.toLowerCase())) ||
        pet.species.toLowerCase().includes(searchTerm.toLowerCase())
    );
    renderPets(filtered);
}

document.addEventListener('DOMContentLoaded', function() {
    const hour = new Date().getHours();
    const greetingText = document.querySelector('.greeting-text');
    if (greetingText) {
        let greeting = 'Hola, buenas tardes';
        if (hour < 12) greeting = 'Hola, buenos días';
        else if (hour >= 18) greeting = 'Hola, buenas noches';
        greetingText.textContent = greeting + ' 👋';
    }

    loadPets();

    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            filterPets(e.target.value.toLowerCase());
        });
    }

    const buscador = document.querySelector('.search-input');
    const botonesNav = document.querySelectorAll('.nav-item');
    const btnExplorar = botonesNav[1];

    if (btnExplorar && buscador) {
        btnExplorar.addEventListener('click', function(e) {
            e.preventDefault();
            buscador.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                buscador.focus();
                buscador.style.outline = "3px solid #634832";
                buscador.style.transition = "outline 0.3s ease";
                setTimeout(() => { buscador.style.outline = "none"; }, 1000);
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
