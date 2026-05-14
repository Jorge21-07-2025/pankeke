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

    renderPets(filtered);
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
        setTimeout(() => irA(accion), 300);
    }

    updateBreedsList('Perro');
    setupSpeciesTabs();
    setupImagePreview();
    setupPublicarForm();
    setupFiltros();
});

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
                const modal = document.getElementById('modal-favoritos');
                if (modal) modal.style.display = 'block';
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

    if (btnGuardados && modalFavoritos) {
        btnGuardados.addEventListener('click', function(e) {
            e.preventDefault();
            cargarFavoritosServidor();
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
const modalChatRespuesta = document.getElementById('modal-chat-respuesta');
const closeChatRespuesta = document.getElementById('close-chat-respuesta');
const chatRespuestaInput = document.getElementById('chat-respuesta-input');
const btnEnviarRespuesta = document.getElementById('btn-enviar-respuesta');
const chatRespuestaMsgs = document.getElementById('chat-respuesta-msgs');
const chatRespuestaTitulo = document.getElementById('chat-respuesta-titulo');

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
            <div class="conversacion-card" data-user-id="${conv.user.id}" data-pet-id="${conv.pet.id}" data-user-name="${conv.user.name}" data-pet-name="${conv.pet.name}" style="padding: 12px; border-bottom: 1px solid var(--bg-light); cursor: pointer; transition: background 0.2s;">
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
                chatActualUserId = this.dataset.userId;
                chatActualPetId = this.dataset.petId;
                const petName = this.dataset.petName;
                const userName = this.dataset.userName;
                chatRespuestaTitulo.textContent = '💬 ' + petName + ' · ' + userName;
                modalMensajes.style.display = 'none';
                modalChatRespuesta.style.display = 'block';
                cargarChatRespuesta();
            });
        });

        actualizarBadgeMensajes(data.unread_count);
    } catch (error) {
        container.innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 20px;">Error al cargar mensajes</p>';
    }
}

async function cargarChatRespuesta() {
    if (!chatActualUserId || !chatActualPetId) return;

    chatRespuestaMsgs.innerHTML = '<p style="text-align: center; color: var(--text-light); font-size: 13px; margin: auto;">Cargando mensajes...</p>';

    try {
        const response = await fetch('/mensajes/chat?user_id=' + chatActualUserId + '&pet_id=' + chatActualPetId, {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();

        if (!data.messages || data.messages.length === 0) {
            chatRespuestaMsgs.innerHTML = '<p style="text-align: center; color: var(--text-light); font-size: 13px; margin: auto;">Sin mensajes aún</p>';
            return;
        }

        const userId = typeof CURRENT_USER_ID !== 'undefined' ? CURRENT_USER_ID : 0;

        chatRespuestaMsgs.innerHTML = data.messages.map(msg => {
            const esMio = msg.from_user_id === userId;
            return '<div style="display: flex; justify-content: ' + (esMio ? 'flex-end' : 'flex-start') + ';">' +
                '<div style="max-width: 80%; padding: 8px 12px; border-radius: var(--radius-md); background: ' + (esMio ? 'var(--primary-orange)' : 'white') + '; color: ' + (esMio ? 'white' : 'var(--text-dark)') + '; font-size: 14px;">' +
                msg.message +
                '<div style="font-size: 10px; opacity: 0.7; margin-top: 4px;">' + new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + '</div>' +
                '</div></div>';
        }).join('');

        chatRespuestaMsgs.scrollTop = chatRespuestaMsgs.scrollHeight;
    } catch (error) {
        chatRespuestaMsgs.innerHTML = '<p style="text-align: center; color: #e74c3c; font-size: 13px;">Error al cargar mensajes</p>';
    }
}

async function enviarRespuesta() {
    const texto = chatRespuestaInput.value.trim();
    if (!texto || !chatActualUserId || !chatActualPetId) return;

    btnEnviarRespuesta.disabled = true;
    btnEnviarRespuesta.textContent = 'Enviando...';

    try {
        const response = await fetch('/mensajes/' + chatActualPetId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: texto }),
        });

        const data = await response.json();

        if (data.success) {
            chatRespuestaInput.value = '';
            cargarChatRespuesta();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al enviar mensaje');
    } finally {
        btnEnviarRespuesta.disabled = false;
        btnEnviarRespuesta.textContent = 'Enviar';
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

if (closeChatRespuesta) {
    closeChatRespuesta.onclick = function() {
        modalChatRespuesta.style.display = 'none';
    }
}

window.addEventListener('click', function(event) {
    if (event.target == modalChatRespuesta) {
        modalChatRespuesta.style.display = 'none';
    }
});

if (btnEnviarRespuesta && chatRespuestaInput) {
    btnEnviarRespuesta.addEventListener('click', enviarRespuesta);
    chatRespuestaInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') enviarRespuesta();
    });
}

setInterval(revisarMensajesNoLeidos, 10000);
revisarMensajesNoLeidos();
