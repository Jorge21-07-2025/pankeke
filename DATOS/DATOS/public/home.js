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
let currentPage = 1;
let hasMore = true;
let cargandoMas = false;

async function loadPets() {
    currentPage = 1;
    hasMore = true;
    allPets = [];
    await cargarPagina(1, true);
}

async function cargarPagina(pagina, reemplazar) {
    if (cargandoMas) return;
    cargandoMas = true;

    try {
        const response = await fetch('/mascotas/json?page=' + pagina + '&per_page=12');
        const data = await response.json();

        if (reemplazar) {
            allPets = data.pets;
        } else {
            allPets = allPets.concat(data.pets);
        }

        hasMore = data.has_more;
        currentPage = pagina;
        renderPets(allPets, hasMore);
    } catch (error) {
        console.error('Error loading pets:', error);
        if (reemplazar) showError();
    } finally {
        cargandoMas = false;
    }
}

async function cargarMas() {
    if (!hasMore || cargandoMas) return;
    await cargarPagina(currentPage + 1, false);
}

function renderPets(pets, mostrarMas) {
    const petsGrid = document.getElementById('petsGrid');
    if (!petsGrid) return;

    if (pets.length === 0) {
        petsGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--text-medium); padding: 40px;">No se encontraron mascotas</p>';
        return;
    }

    let html = pets.map(pet => `
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

    if (mostrarMas) {
        html += '<div style="grid-column: 1/-1; text-align: center; padding: 20px 0;">' +
            '<button onclick="cargarMas()" id="btn-cargar-mas" style="padding: 12px 32px; background: var(--bg-white); border: 2px solid var(--primary-orange); color: var(--primary-orange); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Cargar más mascotas</button>' +
            '</div>';
    }

    petsGrid.innerHTML = html;
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
    const especie = document.getElementById('filtro-especie')?.value || '';
    const tamano = document.getElementById('filtro-tamano')?.value || '';
    const genero = document.getElementById('filtro-genero')?.value || '';
    const ciudad = document.getElementById('filtro-ciudad')?.value || '';

    const filtered = allPets.filter(pet => {
        if (searchTerm) {
            const term = searchTerm.toLowerCase();
            const matchesSearch =
                pet.name.toLowerCase().includes(term) ||
                (pet.breed && pet.breed.toLowerCase().includes(term)) ||
                (pet.city && pet.city.toLowerCase().includes(term)) ||
                pet.species.toLowerCase().includes(term) ||
                (pet.description && pet.description.toLowerCase().includes(term));
            if (!matchesSearch) return false;
        }

        if (especie && pet.species !== especie) return false;
        if (tamano && pet.size !== tamano) return false;
        if (genero && pet.gender !== genero) return false;
        if (ciudad && pet.city && !pet.city.toLowerCase().includes(ciudad.toLowerCase())) return false;

        return true;
    });

    renderPets(filtered, false);
}

function setupFiltros() {
    const btnFiltro = document.getElementById('btn-filtro');
    const panel = document.getElementById('filtros-panel');
    if (!btnFiltro || !panel) return;

    btnFiltro.addEventListener('click', function() {
        panel.classList.toggle('visible');
        this.classList.toggle('active');
    });

    const inputs = panel.querySelectorAll('select, input');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            const searchTerm = document.querySelector('.search-input')?.value || '';
            filterPets(searchTerm);
        });
        input.addEventListener('input', function() {
            const searchTerm = document.querySelector('.search-input')?.value || '';
            filterPets(searchTerm);
        });
    });
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

    const params = new URLSearchParams(window.location.search);
    const accion = params.get('accion');
    if (accion) {
        if (accion === 'guardados') {
            window.location.href = '/guardados';
            return;
        }
        setTimeout(() => irA(accion), 300);
    }

    updateBreedsList('Perro');
    setupSpeciesTabs();
    setupImagePreview();
    setupPublicarForm();
    setupFiltros();
});

function volverAlInicio() {
    if (window.history.replaceState) {
        const urlBase = window.location.pathname;
        window.history.replaceState({}, '', urlBase);
    }
    const botones = document.querySelectorAll('.nav-item');
    botones.forEach(b => b.classList.remove('active'));
    if (botones[0]) botones[0].classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function irA(seccion) {
    const enDashboard = document.getElementById('petsGrid') !== null;
    const orden = ['inicio', 'explorar', 'reportar', 'guardados', 'publicar', 'perfil'];
    const botones = document.querySelectorAll('.nav-item');
    const idx = orden.indexOf(seccion);
    if (idx !== -1 && botones[idx]) {
        botones.forEach(b => b.classList.remove('active'));
        botones[idx].classList.add('active');
    }

    if (seccion === 'perfil') {
        window.location.href = '/perfil';
        return;
    }

    if (seccion === 'guardados') {
        window.location.href = '/guardados';
        return;
    }

    if (enDashboard) {
        switch (seccion) {
            case 'inicio':
                window.scrollTo({ top: 0, behavior: 'smooth' });
                break;
            case 'explorar': {
                const buscador = document.querySelector('.search-input');
                if (buscador) {
                    buscador.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => buscador.focus(), 600);
                }
                break;
            }
            case 'reportar': {
                const modal = document.getElementById('modal-reportar');
                if (modal) modal.style.display = 'block';
                break;
            }
            case 'guardados': {
                window.location.href = '/guardados';
                break;
            }
            case 'publicar': {
                const modal = document.getElementById('modal-publicar');
                if (modal) modal.style.display = 'block';
                break;
            }
        }
    } else {
        window.location.href = '/dashboard?accion=' + seccion;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const btnGuardados = document.getElementById('btn-guardados');
    const modalFavoritos = document.getElementById('modal-favoritos');
    const btnCerrarModal = document.querySelector('.close-button');

    async function cargarFavoritosServidor() {
        const container = document.getElementById('lista-favoritos');
        if (!container) return;

        container.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">Cargando favoritos...</p>';

        try {
            const response = await fetch('/favoritos');
            const data = await response.json();

            if (!data.favorites || data.favorites.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">Aún no has guardado ninguna mascota. <br>¡Explora y dales amor! ❤️</p>';
                return;
            }

            container.innerHTML = data.favorites.map(f => `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--bg-light);">
                    <div>
                        <strong>🐾 ${f.pet.name}</strong>
                        <span style="font-size: 13px; color: var(--text-medium);"> · ${f.pet.breed}</span>
                    </div>
                    <a href="/mascota/${f.pet.id}" style="padding: 6px 12px; background: var(--primary-orange); color: white; text-decoration: none; border-radius: var(--radius-sm); font-size: 12px;">Ver</a>
                </div>
            `).join('');
        } catch (error) {
            container.innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 20px;">Error al cargar favoritos</p>';
        }
    }

    if (btnCerrarModal) {
        btnCerrarModal.onclick = function() {
            modalFavoritos.style.display = "none";
            volverAlInicio();
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalFavoritos) {
            modalFavoritos.style.display = "none";
            volverAlInicio();
        }
    });
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
            volverAlInicio();
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalReportar) {
            modalReportar.style.display = "none";
            volverAlInicio();
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
            volverAlInicio();
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalPublicar) {
            modalPublicar.style.display = "none";
            volverAlInicio();
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
                    <span class="solicitud-status">${req.status === 'en_proceso' ? '⏳ Pendiente' : req.status === 'aprobado' ? '✅ Aprobado' : '❌ Rechazado'}</span>
                </div>
                <div class="solicitud-info"><strong>De:</strong> ${req.user.name}</div>
                <div class="solicitud-info"><strong>Email:</strong> ${req.user.email}</div>
                ${req.phone ? `<div class="solicitud-info"><strong>Teléfono:</strong> ${req.phone}</div>` : ''}
                ${req.message ? `<div class="solicitud-message">"${req.message}"</div>` : ''}
                ${req.status === 'en_proceso' ? `
                    <div style="display: flex; gap: 8px; margin-top: 10px;">
                        <button class="btn-aprobar" data-id="${req.id}" style="flex: 1; padding: 8px; background: var(--secondary-green-light); color: var(--secondary-green-dark); border: none; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; font-size: 13px;">✅ Aprobar</button>
                        <button class="btn-rechazar" data-id="${req.id}" style="flex: 1; padding: 8px; background: #fde8e8; color: #e74c3c; border: none; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; font-size: 13px;">❌ Rechazar</button>
                    </div>
                ` : ''}
            </div>
        `).join('');

        container.querySelectorAll('.btn-aprobar').forEach(btn => {
            btn.addEventListener('click', function() {
                actualizarSolicitud(this.dataset.id, 'aprobado');
            });
        });
        container.querySelectorAll('.btn-rechazar').forEach(btn => {
            btn.addEventListener('click', function() {
                actualizarSolicitud(this.dataset.id, 'rechazado');
            });
        });
    } catch (error) {
        container.innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 20px;">Error al cargar solicitudes</p>';
    }
}

async function actualizarSolicitud(id, status) {
    try {
        const response = await fetch('/solicitudes/' + id, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status }),
        });

        const data = await response.json();

        if (data.success) {
            cargarSolicitudes();
            const badge = document.getElementById('notif-badge');
            if (badge) {
                const count = parseInt(badge.textContent) - 1;
                badge.textContent = count > 0 ? count : '';
                if (count <= 0) badge.style.display = 'none';
            }
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al conectar con el servidor');
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

const btnMensajes = document.getElementById('btn-mensajes');
const modalMensajes = document.getElementById('modal-mensajes');
const closeMensajes = document.getElementById('close-mensajes');
const chatSidebar = document.getElementById('chat-sidebar');
const chatMain = document.getElementById('chat-main');
const chatPlaceholder = document.getElementById('chat-placeholder');
const chatActive = document.getElementById('chat-active');
const chatActiveMsgs = document.getElementById('chat-active-msgs');
const chatActiveTitle = document.getElementById('chat-active-title');
const chatActiveInput = document.getElementById('chat-active-input');
const btnEnviarChatActivo = document.getElementById('btn-enviar-chat-activo');
const chatBackBtn = document.getElementById('chat-back-btn');

let chatActualUserId = null;
let chatActualPetId = null;

async function cargarConversaciones() {
    const container = document.getElementById('lista-conversaciones');
    if (!container) return;

    container.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">Cargando conversaciones...</p>';

    try {
        const response = await fetch('/mensajes', {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();

        if (!data.conversations || data.conversations.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">No tienes mensajes aún.</p>';
            return;
        }

        container.innerHTML = data.conversations.map(conv => `
            <div class="conversacion-card" data-user-id="${conv.user.id}" data-pet-id="${conv.pet.id}" data-user-name="${conv.user.name}" data-pet-name="${conv.pet.name}">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="font-size: 15px;">🐾 ${conv.pet.name}</strong>
                        <span style="font-size: 12px; color: var(--text-medium);"> · ${conv.user.name}</span>
                    </div>
                    ${conv.unread ? '<span style="background: var(--primary-orange); color: white; font-size: 11px; padding: 2px 8px; border-radius: 10px;">Nuevo</span>' : ''}
                </div>
                <p style="font-size: 13px; color: var(--text-medium); margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${conv.last_message}</p>
            </div>
        `).join('');

        container.querySelectorAll('.conversacion-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.conversacion-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                chatActualUserId = this.dataset.userId;
                chatActualPetId = this.dataset.petId;
                const petName = this.dataset.petName;
                const userName = this.dataset.userName;
                chatActiveTitle.textContent = '🐾 ' + petName + ' · ' + userName;

                chatPlaceholder.style.display = 'none';
                chatActive.style.display = 'flex';

                if (window.innerWidth <= 640) {
                    chatSidebar.classList.add('hidden');
                }

                cargarChatActivo();
            });
        });

        actualizarBadgeMensajes(data.unread_count);
    } catch (error) {
        container.innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 20px;">Error al cargar mensajes</p>';
    }
}

async function cargarChatActivo() {
    if (!chatActualUserId || !chatActualPetId) return;

    chatActiveMsgs.innerHTML = '<p style="text-align: center; color: var(--text-light); font-size: 13px; margin: auto;">Cargando mensajes...</p>';

    try {
        const response = await fetch('/mensajes/chat?user_id=' + chatActualUserId + '&pet_id=' + chatActualPetId, {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();

        if (!data.messages || data.messages.length === 0) {
            chatActiveMsgs.innerHTML = '<p style="text-align: center; color: var(--text-light); font-size: 13px; margin: auto;">Sin mensajes aún</p>';
            return;
        }

        const userId = typeof CURRENT_USER_ID !== 'undefined' ? CURRENT_USER_ID : 0;

        chatActiveMsgs.innerHTML = data.messages.map(msg => {
            const esMio = msg.from_user_id === userId;
            return '<div style="display: flex; justify-content: ' + (esMio ? 'flex-end' : 'flex-start') + ';">' +
                '<div style="max-width: 80%; padding: 8px 12px; border-radius: var(--radius-md); background: ' + (esMio ? 'var(--primary-orange)' : 'white') + '; color: ' + (esMio ? 'white' : 'var(--text-dark)') + '; font-size: 14px;">' +
                msg.message +
                '<div style="font-size: 10px; opacity: 0.7; margin-top: 4px;">' + new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + '</div>' +
                '</div></div>';
        }).join('');

        chatActiveMsgs.scrollTop = chatActiveMsgs.scrollHeight;
    } catch (error) {
        chatActiveMsgs.innerHTML = '<p style="text-align: center; color: #e74c3c; font-size: 13px;">Error al cargar mensajes</p>';
    }
}

async function enviarChatActivo() {
    const texto = chatActiveInput.value.trim();
    if (!texto || !chatActualUserId || !chatActualPetId) return;

    btnEnviarChatActivo.disabled = true;
    btnEnviarChatActivo.textContent = 'Enviando...';

    try {
        const response = await fetch('/mensajes/' + chatActualPetId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: texto, to_user_id: chatActualUserId }),
        });

        const data = await response.json();

        if (data.success) {
            chatActiveInput.value = '';
            cargarChatActivo();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al enviar mensaje');
    } finally {
        btnEnviarChatActivo.disabled = false;
        btnEnviarChatActivo.textContent = 'Enviar';
    }
}

async function actualizarBadgeMensajes(count) {
    const badge = document.getElementById('msg-badge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'inline';
    } else {
        badge.style.display = 'none';
    }
}

async function revisarMensajesNoLeidos() {
    try {
        const response = await fetch('/mensajes/no-leidos', {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();
        actualizarBadgeMensajes(data.unread);
    } catch (error) {
        // silencioso
    }
}

if (btnMensajes && modalMensajes) {
    btnMensajes.addEventListener('click', function(e) {
        e.preventDefault();
        cargarConversaciones();
        modalMensajes.style.display = 'block';
    });

    if (closeMensajes) {
        closeMensajes.onclick = function() {
            modalMensajes.style.display = 'none';
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalMensajes) {
            modalMensajes.style.display = 'none';
        }
    });
}

if (chatBackBtn) {
    chatBackBtn.addEventListener('click', function() {
        chatActive.style.display = 'none';
        chatPlaceholder.style.display = 'flex';
        chatSidebar.classList.remove('hidden');
        document.querySelectorAll('.conversacion-card').forEach(c => c.classList.remove('active'));
    });
}

if (btnEnviarChatActivo && chatActiveInput) {
    btnEnviarChatActivo.addEventListener('click', enviarChatActivo);
    chatActiveInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') enviarChatActivo();
    });
}

// --- EMERGENCIAS ---

const btnEmergencia = document.getElementById('btn-emergencia');
const modalEmergencia = document.getElementById('modal-emergencia');
const closeEmergencia = document.getElementById('close-emergencia');
const formEmergencia = document.getElementById('form-emergencia');
const btnGps = document.getElementById('btn-gps');
const emergenciaLocation = document.getElementById('emergencia-location');
const emergenciaLat = document.getElementById('emergencia-lat');
const emergenciaLng = document.getElementById('emergencia-lng');
const emergenciaCoords = document.getElementById('emergencia-coords');

if (btnEmergencia && modalEmergencia) {
    btnEmergencia.addEventListener('click', function() {
        document.getElementById('modal-reportar').style.display = 'none';
        modalEmergencia.style.display = 'block';
    });
}

if (closeEmergencia && modalEmergencia) {
    closeEmergencia.onclick = function() {
        modalEmergencia.style.display = 'none';
        volverAlInicio();
    };
    window.addEventListener('click', function(event) {
        if (event.target == modalEmergencia) {
            modalEmergencia.style.display = 'none';
            volverAlInicio();
        }
    });
}

if (btnGps) {
    btnGps.addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Tu navegador no soporta la ubicación. Escribe la dirección manualmente.');
            return;
        }
        btnGps.textContent = '📍 Obteniendo ubicación...';
        btnGps.disabled = true;
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                emergenciaLat.value = pos.coords.latitude;
                emergenciaLng.value = pos.coords.longitude;
                emergenciaCoords.textContent = '📍 Coordenadas: ' + pos.coords.latitude + ', ' + pos.coords.longitude;
                btnGps.textContent = '✅ Ubicación obtenida';
                btnGps.style.background = '#27ae60';
            },
            function() {
                alert('No se pudo obtener la ubicación. Escribe la dirección manualmente.');
                btnGps.textContent = '📍 Usar mi ubicación';
                btnGps.disabled = false;
            }
        );
    });
}

if (formEmergencia) {
    formEmergencia.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('.btn-submit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/emergencias', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.success) {
                modalEmergencia.style.display = 'none';
                this.reset();
                emergenciaCoords.textContent = '';
                btnGps.textContent = '📍 Usar mi ubicación';
                btnGps.disabled = false;
                btnGps.style.background = '';
                alert('✅ ' + result.message);
                volverAlInicio();
            } else {
                alert('Error: ' + (result.message || 'No se pudo reportar'));
            }
        } catch (error) {
            alert('Error al conectar con el servidor');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '🚨 Reportar emergencia';
        }
    });
}

// Emergencias activas para rescatistas
const btnEmergenciasActivas = document.getElementById('btn-emergencias-activas');
const modalEmergenciasActivas = document.getElementById('modal-emergencias-activas');
const closeEmergenciasActivas = document.getElementById('close-emergencias-activas');

async function cargarEmergenciasActivas() {
    const container = document.getElementById('lista-emergencias');
    if (!container) return;

    try {
        const response = await fetch('/emergencias/activas', {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();

        if (!data.emergencies || data.emergencies.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">No hay emergencias activas. 🐾</p>';
            return;
        }

        container.innerHTML = data.emergencies.map(emerg => `
            <div class="solicitud-card" style="border-left-color: #e74c3c;">
                <div class="solicitud-header">
                    <span class="solicitud-pet-name">🚨 Emergencia</span>
                    <span class="solicitud-status" style="background: #e74c3c; color: white;">${emerg.status === 'pendiente' ? 'Pendiente' : 'En curso'}</span>
                </div>
                <div class="solicitud-info"><strong>Reportó:</strong> ${emerg.user.name}</div>
                <div class="solicitud-message" style="background: var(--bg-white);">"${emerg.description}"</div>
                ${emerg.location ? '<div class="solicitud-info">📍 ' + emerg.location + '</div>' : ''}
                ${emerg.assignments && emerg.assignments.length > 0 ? `
                    <div style="margin-top: 8px; padding: 8px; background: #e8f5e9; border-radius: var(--radius-sm);">
                        <strong style="font-size: 13px;">🦸 Rescatistas:</strong>
                        ${emerg.assignments.map(a => '<div style="font-size: 13px; color: var(--text-dark);">✅ ' + a.user.name + ': ' + (a.message || 'En camino') + '</div>').join('')}
                    </div>
                ` : ''}
                <div style="display: flex; gap: 8px; margin-top: 10px;">
                    ${emerg.status !== 'resuelto' ? `
                        <button class="btn-asignar" data-id="${emerg.id}" style="flex: 1; padding: 8px; background: var(--secondary-green-light); color: var(--secondary-green-dark); border: none; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; font-size: 13px;">🦸 Voy en camino</button>
                        <button class="btn-resolver" data-id="${emerg.id}" style="flex: 1; padding: 8px; background: #fde8e8; color: #e74c3c; border: none; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; font-size: 13px;">✅ Resuelto</button>
                    ` : ''}
                </div>
                ${emerg.status === 'pendiente' ? `
                    <div style="margin-top: 8px;">
                        <input type="text" class="emergencia-mensaje" data-id="${emerg.id}" placeholder="Ej: Ya voy para allá..." style="width: 100%; padding: 8px; border: 2px solid var(--bg-light); border-radius: var(--radius-sm); font-size: 13px; box-sizing: border-box;">
                    </div>
                ` : ''}
            </div>
        `).join('');

        container.querySelectorAll('.btn-asignar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const msgInput = container.querySelector('.emergencia-mensaje[data-id="' + id + '"]');
                const message = msgInput ? msgInput.value : '';
                const msg = prompt('Escribe un mensaje (opcional):', message || '¡Ya voy en camino!');
                if (msg !== null) {
                    asignarEmergencia(id, msg);
                }
            });
        });

        container.querySelectorAll('.btn-resolver').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('¿Estás seguro de que esta emergencia está resuelta?')) {
                    resolverEmergencia(this.dataset.id);
                }
            });
        });

        const badge = document.getElementById('emergencia-badge');
        if (badge) {
            const pendientes = data.emergencies.filter(e => e.status === 'pendiente').length;
            if (pendientes > 0) {
                badge.textContent = pendientes;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Error loading emergencies:', error);
    }
}

async function asignarEmergencia(id, message) {
    try {
        const response = await fetch('/emergencias/' + id + '/asignar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: message }),
        });

        const data = await response.json();

        if (data.success) {
            alert('✅ ' + data.message);
            cargarEmergenciasActivas();
        } else {
            alert('Error: ' + (data.message || 'No se pudo asignar'));
        }
    } catch (error) {
        alert('Error al conectar con el servidor');
    }
}

async function resolverEmergencia(id) {
    try {
        const response = await fetch('/emergencias/' + id + '/resolver', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (data.success) {
            alert('✅ ' + data.message);
            cargarEmergenciasActivas();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al conectar con el servidor');
    }
}

if (btnEmergenciasActivas && modalEmergenciasActivas) {
    btnEmergenciasActivas.addEventListener('click', function(e) {
        e.preventDefault();
        cargarEmergenciasActivas();
        modalEmergenciasActivas.style.display = 'block';
    });

    if (closeEmergenciasActivas) {
        closeEmergenciasActivas.onclick = function() {
            modalEmergenciasActivas.style.display = 'none';
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == modalEmergenciasActivas) {
            modalEmergenciasActivas.style.display = 'none';
        }
    });
}

setInterval(revisarMensajesNoLeidos, 10000);
revisarMensajesNoLeidos();

if (document.getElementById('btn-emergencias-activas')) {
    setInterval(cargarEmergenciasActivas, 15000);
}
