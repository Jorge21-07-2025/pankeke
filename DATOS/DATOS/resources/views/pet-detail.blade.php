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
                    <div class="info-value">{{ $pet->weight ?? '—' }}</div>
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

            <button class="btn-adopt" id="adoptBtn">
                🐾 Quiero adoptarlo
            </button>
        </main>

        <nav class="bottom-nav">
            <button class="nav-item" onclick="window.location.href='{{ route('dashboard') }}'">
                <span class="nav-icon">🏠</span>
                <span class="nav-label">Inicio</span>
            </button>
            <button class="nav-item">
                <span class="nav-icon">🔍</span>
                <span class="nav-label">Explorar</span>
            </button>
            <button class="nav-item">
                <span class="nav-icon">📢</span>
                <span class="nav-label">Reportar</span>
            </button>
            <button class="nav-item">
                <span class="nav-icon">❤️</span>
                <span class="nav-label">Guardados</span>
            </button>
            <button class="nav-item" onclick="window.location.href='{{ route('perfil') }}'">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Perfil</span>
            </button>
        </nav>
    </div>

    <div id="modal-adoptar" class="modal" style="display: none;">
        <div class="modal-content modal-adoptar-content">
            <span class="close-button" id="close-adoptar">&times;</span>
            <h2 style="color: #634832;">🐾 Adoptar a {{ $pet->name }}</h2>
            <p style="color: var(--text-medium); font-size: 14px; margin-bottom: 16px;">
                Tu solicitud será enviada al dueño de {{ $pet->name }}
            </p>
            <form id="form-adoptar">
                @csrf
                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label">Tu teléfono de contacto</label>
                    <input type="text" name="phone" class="form-input" placeholder="Ej: 300 123 4567">
                </div>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Mensaje (opcional)</label>
                    <textarea name="message" class="form-input form-textarea" placeholder="Cuéntale al dueño por qué quieres adoptar a {{ $pet->name }}..."></textarea>
                </div>
                <button type="submit" class="btn-submit" id="btn-enviar-solicitud">
                    🐾 Enviar solicitud
                </button>
            </form>
        </div>
    </div>

    <script>
        const petId = {{ $pet->id }};

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '{{ route('dashboard') }}';
            }
        }

        function toggleFavorite() {
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const index = favorites.indexOf(petId);

            if (index > -1) {
                favorites.splice(index, 1);
            } else {
                favorites.push(petId);
            }

            localStorage.setItem('favorites', JSON.stringify(favorites));
            updateFavoriteButton();
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

        function openAdoptModal() {
            const modal = document.getElementById('modal-adoptar');
            if (modal) modal.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateFavoriteButton();

            const favoriteBtn = document.getElementById('favoriteBtn');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', toggleFavorite);
            }

            const adoptBtn = document.getElementById('adoptBtn');
            if (adoptBtn) {
                adoptBtn.addEventListener('click', openAdoptModal);
            }

            const modal = document.getElementById('modal-adoptar');
            const closeBtn = document.getElementById('close-adoptar');

            if (closeBtn) {
                closeBtn.onclick = function() {
                    modal.style.display = 'none';
                };
            }

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
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
                            modal.style.display = 'none';
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
    </script>
</body>
</html>
