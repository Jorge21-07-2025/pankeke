<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $pet->name }} - Adopción de Mascotas</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('pet-detail.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="loadingState" class="loading-state" style="{{ $pet ? 'display: none;' : '' }}">
        <div class="loading-spinner">Cargando...</div>
    </div>

    <div id="mainContent" class="main-content">
        <header class="pet-header" style="background: linear-gradient(135deg, {{ $pet->color ?? '#6ba587' }}, {{ $pet->color ?? '#6ba587' }}dd);">
            <div class="header-actions">
                <button class="btn-back" onclick="goBack()">
                    <span class="icon">←</span>
                </button>
                <button class="btn-favorite" id="favoriteBtn">
                    <span class="icon">♡</span>
                </button>
            </div>
            <div class="pet-image-wrapper">
                @if ($pet->image)
                    <img id="petImage" src="{{ $pet->image }}" alt="{{ $pet->name }}" class="pet-image"
                         onerror="this.style.display='none'; this.parentElement.innerHTML = '<div style=\'font-size: 120px; background: {{ $pet->color ?? '#6ba587' }}; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);\'>{{ $pet->emoji ?? '🐾' }}</div>';" />
                @else
                    <div style="font-size: 120px; background: {{ $pet->color ?? '#6ba587' }}; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);">{{ $pet->emoji ?? '🐾' }}</div>
                @endif
            </div>
        </header>

        <main class="pet-details">
            <div class="pet-header-info">
                <div>
                    <h1 class="pet-name">{{ $pet->name }}</h1>
                    <p class="pet-basic-info">{{ $pet->breed }} · {{ $pet->age }} {{ $pet->age_unit }} · {{ $pet->gender }} · {{ $pet->city }}</p>
                </div>
                <span class="badge badge-success">{{ $pet->status }}</span>
            </div>

            <div class="traits-container">
                <div class="trait-badge active">
                    <span class="trait-icon">✓</span>
                    <span>Vacunado</span>
                </div>
                <div class="trait-badge {{ $pet->gender === 'Hembra' ? 'inactive' : 'active' }}">
                    <span class="trait-icon">✂️</span>
                    <span>Castrado</span>
                </div>
                <div class="trait-badge active">
                    <span class="trait-icon">😊</span>
                    <span>Sociable</span>
                </div>
                <div class="trait-badge inactive">
                    <span class="trait-icon">🎓</span>
                    <span>Entrenado</span>
                </div>
            </div>

            <div class="info-cards">
                <div class="info-card">
                    <div class="info-value">{{ $pet->weight ? (is_numeric($pet->weight) ? $pet->weight . ' kg' : $pet->weight) : '—' }}</div>
                    <div class="info-label">Peso</div>
                </div>
                <div class="info-card">
                    <div class="info-value">{{ $pet->size ?? '—' }}</div>
                    <div class="info-label">Tamaño</div>
                </div>
                <div class="info-card">
                    <div class="info-value">{{ $pet->shelter?->name ?? 'No especificado' }}</div>
                    <div class="info-label">Refugio</div>
                </div>
            </div>

            <div class="description-section">
                <h2 class="section-title">Acerca de mí</h2>
                <p class="description-text">{{ $pet->description ?? 'Sin descripción disponible.' }}</p>
            </div>

            @if(Auth::id() !== $pet->user_id)
                <button class="btn-adopt" id="adoptBtn" style="margin-bottom: 10px;">
                    🐾 Quiero adoptarlo
                </button>
                <button class="btn-adopt" id="btn-contactar" style="background: linear-gradient(135deg, var(--secondary-green), var(--secondary-green-light));">
                    💬 Contactar dueño
                </button>
            @endif
        </main>

        <nav class="bottom-nav">
            <button class="nav-item" onclick="irA('inicio')">
                <span class="nav-icon">🏠</span>
                <span class="nav-label">Inicio</span>
            </button>
            <button class="nav-item" onclick="irA('explorar')">
                <span class="nav-icon">🔍</span>
                <span class="nav-label">Explorar</span>
            </button>
            <button class="nav-item" onclick="irA('reportar')">
                <span class="nav-icon">📢</span>
                <span class="nav-label">Reportar</span>
            </button>
            <button class="nav-item" onclick="irA('guardados')">
                <span class="nav-icon">❤️</span>
                <span class="nav-label">Guardados</span>
            </button>
            <button class="nav-item" onclick="irA('publicar')">
                <span class="nav-icon">➕</span>
                <span class="nav-label">Publicar</span>
            </button>
            <button class="nav-item" onclick="irA('perfil')">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Perfil</span>
            </button>
        </nav>
    </div>

    <div id="modal-adoptar" class="modal" style="display: none;">
        <div class="modal-content modal-adoptar-content">
            <span class="close-button" id="close-adoptar">&times;</span>
            <div class="modal-header-pet">
                <span class="pet-big-emoji">{{ $pet->emoji ?? '🐾' }}</span>
                <h2>Adoptar a {{ $pet->name }}</h2>
                <p>Tu solicitud será enviada al dueño</p>
            </div>
            <div class="modal-body">
                <form id="form-adoptar">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">📞 Tu teléfono</label>
                        <input type="text" name="phone" class="form-input" placeholder="Ej: 300 123 4567">
                    </div>
                    <div class="form-group">
                        <label class="form-label">💬 Mensaje (opcional)</label>
                        <textarea name="message" class="form-input form-textarea" placeholder="Cuéntale al dueño por qué quieres adoptar a {{ $pet->name }}..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit" id="btn-enviar-solicitud">
                        🐾 Enviar solicitud
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-chat" class="modal" style="display: none;">
        <div class="modal-content modal-chat-content">
            <span class="close-button" id="close-chat">&times;</span>
            <div class="chat-header">
                <div class="chat-header-avatar">{{ $pet->emoji ?? '🐾' }}</div>
                <div class="chat-header-info">
                    <h2>💬 {{ $pet->name }}</h2>
                    <p>Habla con el dueño sobre la adopción</p>
                </div>
            </div>
            <div class="chat-messages-container" id="chat-mensajes">
                <p style="text-align: center; color: #999; font-size: 13px; margin: auto;">Escribe un mensaje para empezar</p>
            </div>
            <div class="chat-input-area">
                <input type="text" id="chat-input" placeholder="Escribe tu mensaje...">
                <button id="btn-enviar-chat"><span id="btn-chat-text">➤</span></button>
            </div>
        </div>
    </div>

    <script>
        const petId = {{ $pet->id }};
        const petOwnerId = {{ $pet->user_id }};

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '{{ route('dashboard') }}';
            }
        }

        let favoritoServidor = false;

        async function toggleFavorite() {
            const favoriteBtn = document.getElementById('favoriteBtn');
            favoriteBtn.style.pointerEvents = 'none';

            try {
                const response = await fetch('/favoritos/' + petId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();
                favoritoServidor = data.favorited;

                let localFavs = JSON.parse(localStorage.getItem('favorites') || '[]');
                const idx = localFavs.indexOf(petId);
                if (data.favorited && idx === -1) localFavs.push(petId);
                if (!data.favorited && idx > -1) localFavs.splice(idx, 1);
                localStorage.setItem('favorites', JSON.stringify(localFavs));

                updateFavoriteButton();
            } catch (error) {
                console.error('Error:', error);
            } finally {
                favoriteBtn.style.pointerEvents = '';
            }
        }

        async function checkFavoriteServer() {
            try {
                const response = await fetch('/favoritos');
                const data = await response.json();
                favoritoServidor = data.favorites.some(f => f.pet_id === petId);

                let localFavs = JSON.parse(localStorage.getItem('favorites') || '[]');
                if (favoritoServidor && !localFavs.includes(petId)) localFavs.push(petId);
                if (!favoritoServidor && localFavs.includes(petId)) {
                    localFavs = localFavs.filter(id => id !== petId);
                }
                localStorage.setItem('favorites', JSON.stringify(localFavs));

                updateFavoriteButton();
            } catch (error) {
                const local = JSON.parse(localStorage.getItem('favorites') || '[]');
                favoritoServidor = local.includes(petId);
                updateFavoriteButton();
            }
        }

        function updateFavoriteButton() {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const favoriteBtn = document.getElementById('favoriteBtn');
            const isFavorite = favorites.includes(petId);

            if (isFavorite) {
                favoriteBtn.classList.add('active');
                favoriteBtn.querySelector('.icon').textContent = '♥';
            } else {
                favoriteBtn.classList.remove('active');
                favoriteBtn.querySelector('.icon').textContent = '♡';
            }
        }

        function cerrarTodosModales() {
            document.getElementById('modal-adoptar').style.display = 'none';
            document.getElementById('modal-chat').style.display = 'none';
        }

        function openAdoptModal() {
            cerrarTodosModales();
            document.getElementById('modal-adoptar').style.display = 'block';
        }

        function openChatModal() {
            cerrarTodosModales();
            document.getElementById('modal-chat').style.display = 'block';
            cargarChat();
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkFavoriteServer();

            const favoriteBtn = document.getElementById('favoriteBtn');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', toggleFavorite);
            }

            const adoptBtn = document.getElementById('adoptBtn');
            if (adoptBtn) {
                adoptBtn.addEventListener('click', openAdoptModal);
            }

            const adoptModal = document.getElementById('modal-adoptar');
            const closeAdopt = document.getElementById('close-adoptar');

            if (closeAdopt) {
                closeAdopt.onclick = function() {
                    adoptModal.style.display = 'none';
                };
            }

            window.addEventListener('click', function(event) {
                if (event.target == adoptModal) {
                    adoptModal.style.display = 'none';
                }
            });

            const form = document.getElementById('form-adoptar');
            const submitBtn = document.getElementById('btn-enviar-solicitud');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Enviando...';

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    try {
                        const response = await fetch('/adoptar/' + petId, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();

                        if (result.success) {
                            adoptModal.style.display = 'none';
                            alert('✅ ' + result.message);
                            this.reset();
                        } else {
                            alert('Error: ' + (result.message || 'No se pudo enviar la solicitud'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al conectar con el servidor');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.textContent = '🐾 Enviar solicitud';
                    }
                });
            }
        });

        const chatModal = document.getElementById('modal-chat');
        const chatClose = document.getElementById('close-chat');
        const chatBtn = document.getElementById('btn-contactar');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('btn-enviar-chat');
        const chatContainer = document.getElementById('chat-mensajes');

        if (chatBtn && chatModal) {
            chatBtn.addEventListener('click', openChatModal);
        }

        if (chatClose) {
            chatClose.onclick = () => chatModal.style.display = 'none';
        }

        window.addEventListener('click', function(event) {
            if (event.target == chatModal) chatModal.style.display = 'none';
        });

        if (chatSend && chatInput) {
            chatSend.addEventListener('click', enviarMensaje);
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') enviarMensaje();
            });
        }

        async function enviarMensaje() {
            const texto = chatInput.value.trim();
            if (!texto) return;

            chatSend.disabled = true;
            chatSend.querySelector('#btn-chat-text').textContent = '...';

            try {
                const response = await fetch('/mensajes/' + petId, {
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
                    chatInput.value = '';
                    cargarChat();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error al enviar mensaje');
            } finally {
                chatSend.disabled = false;
                chatSend.querySelector('#btn-chat-text').textContent = '➤';
            }
        }

        async function cargarChat() {
            try {
                const response = await fetch('/mensajes/chat?user_id=' + petOwnerId + '&pet_id=' + petId, {
                    headers: { 'Accept': 'application/json' },
                });

                const data = await response.json();

                if (!data.messages || data.messages.length === 0) {
                    chatContainer.innerHTML = '<p style="text-align: center; color: var(--text-light); font-size: 13px; margin: auto;">Escribe un mensaje para empezar</p>';
                    return;
                }

                chatContainer.innerHTML = data.messages.map(msg => {
                    const esMio = msg.from_user_id === {{ Auth::id() }};
                    return '<div class="chat-message-bubble ' + (esMio ? 'mine' : 'theirs') + '">' +
                        msg.message +
                        '<div class="chat-message-time">' + new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + '</div>' +
                        '</div>';
                }).join('');

                chatContainer.scrollTop = chatContainer.scrollHeight;
            } catch (error) {
                chatContainer.innerHTML = '<p style="text-align: center; color: #e74c3c; font-size: 13px;">Error al cargar mensajes</p>';
            }
        }
    </script>
</body>
</html>
