const DOG_BREEDS = [
    'Cruce', 'Labrador', 'Pastor Alemán', 'Buldog Francés', 'Poodle',
    'Chihuahua', 'Golden Retriever', 'Husky', 'Beagle', 'Boxer',
    'Dálmata', 'Rottweiler', 'Yorkshire Terrier', 'Schnauzer', 'Shih Tzu',
    'Pug', 'Border Collie', 'Pitbull', 'Doberman', 'Gran Danés',
    'San Bernardo', 'Maltés', 'Pomerania', 'West Highland', 'Otra',
];

const CAT_BREEDS = [
    'Cruce', 'Persa', 'Siamés', 'Maine Coon', 'Bengalí',
    'Sphynx', 'Ragdoll', 'British Shorthair', 'Scottish Fold', 'Abisinio',
    'Angora', 'Burmés', 'Cornish Rex', 'Oriental', 'Otra',
];

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
                    <span class="pet-emoji" style="font-size: 48px;">🐾</span>
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

function updateBreedsList(species) {
    const breedsList = document.getElementById('breeds-list');
    if (!breedsList) return;
    const breeds = species === 'Gato' ? CAT_BREEDS : DOG_BREEDS;
    breedsList.innerHTML = breeds.map(b => `<option value="${b}">`).join('');
}

function setupSpeciesTabs() {
    const tabs = document.querySelectorAll('.species-tab');
    const speciesInput = document.getElementById('input-species');
    if (!tabs.length || !speciesInput) return;

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            speciesInput.value = this.dataset.species;
            updateBreedsList(this.dataset.species);
        });
    });
}

function setupImagePreview() {
    const fileInput = document.getElementById('pet-image-input');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('file-upload-placeholder');
    const removeBtn = document.getElementById('btn-remove-image');
    if (!fileInput || !preview || !placeholder || !removeBtn) return;

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                removeBtn.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        preview.style.display = 'none';
        preview.src = '';
        placeholder.style.display = 'flex';
        removeBtn.style.display = 'none';
    });
}

function setupPublicarForm() {
    const form = document.getElementById('form-publicar');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('.btn-submit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Publicando...';

        const formData = new FormData(this);

        try {
            const response = await fetch('/mascotas', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                const modal = document.getElementById('modal-publicar');
                if (modal) modal.style.display = 'none';
                this.reset();
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('file-upload-placeholder');
                const removeBtn = document.getElementById('btn-remove-image');
                if (preview) preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'flex';
                if (removeBtn) removeBtn.style.display = 'none';
                loadPets();
                alert('✅ ' + data.message);
            } else {
                alert('Error: ' + (data.message || 'No se pudo publicar la mascota'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '🐾 Publicar mascota';
        }
    });
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

    botonesNav.forEach((item, index) => {
        item.addEventListener('click', function() {
            botonesNav.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });

    updateBreedsList('Perro');
    setupSpeciesTabs();
    setupImagePreview();
    setupPublicarForm();
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

const btnPublicar = document.getElementById('btn-publicar');
const modalPublicar = document.getElementById('modal-publicar');
const closePublicar = document.getElementById('close-publicar');

if (btnPublicar && modalPublicar) {
    btnPublicar.addEventListener('click', function(e) {
        e.preventDefault();
        modalPublicar.style.display = "block";
    });

    if (closePublicar) {
        closePublicar.onclick = function() {
            modalPublicar.style.display = "none";
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalPublicar) {
            modalPublicar.style.display = "none";
        }
    });
}

async function cargarSolicitudes() {
    const container = document.getElementById('lista-solicitudes');
    if (!container) return;

    try {
        const response = await fetch('/solicitudes/mias');
        const data = await response.json();

        if (!data.requests || data.requests.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">No tienes solicitudes de adopción pendientes.</p>';
            return;
        }

        container.innerHTML = data.requests.map(req => `
            <div class="solicitud-card">
                <div class="solicitud-header">
                    <span class="solicitud-pet-name">🐾 ${req.pet.name}</span>
                    <span class="solicitud-status">${req.status}</span>
                </div>
                <div class="solicitud-info"><strong>De:</strong> ${req.user.name}</div>
                <div class="solicitud-info"><strong>Email:</strong> ${req.user.email}</div>
                ${req.phone ? `<div class="solicitud-info"><strong>Teléfono:</strong> ${req.phone}</div>` : ''}
                ${req.message ? `<div class="solicitud-message">"${req.message}"</div>` : ''}
            </div>
        `).join('');
    } catch (error) {
        container.innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 20px;">Error al cargar solicitudes</p>';
    }
}

const btnEditarPerfil = document.getElementById('btn-editar-perfil');
const modalEditarPerfil = document.getElementById('modal-editar-perfil');
const closeEditarPerfil = document.getElementById('close-editar-perfil');

if (btnEditarPerfil && modalEditarPerfil) {
    btnEditarPerfil.addEventListener('click', function() {
        modalEditarPerfil.style.display = 'block';
    });

    if (closeEditarPerfil) {
        closeEditarPerfil.onclick = function() {
            modalEditarPerfil.style.display = 'none';
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalEditarPerfil) {
            modalEditarPerfil.style.display = 'none';
        }
    });
}

const btnNotificaciones = document.getElementById('btn-notificaciones');
const modalNotificaciones = document.getElementById('modal-notificaciones');
const closeNotificaciones = document.getElementById('close-notificaciones');

if (btnNotificaciones && modalNotificaciones) {
    btnNotificaciones.addEventListener('click', function(e) {
        e.preventDefault();
        cargarSolicitudes();
        modalNotificaciones.style.display = "block";
    });

    if (closeNotificaciones) {
        closeNotificaciones.onclick = function() {
            modalNotificaciones.style.display = "none";
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalNotificaciones) {
            modalNotificaciones.style.display = "none";
        }
    });
}
