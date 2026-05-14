<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio - Adopción de Mascotas</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="greeting">
                <span class="greeting-text">¡Hola, buenas tardes 👋!</span>
                <h1 class="user-name" id="userName">{{ Auth::user()->name }}</h1>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <button id="btn-notificaciones" style="background: none; border: none; cursor: pointer; position: relative; font-size: 24px;">
                    🔔
                    @if($solicitudesCount > 0)
                        <span class="notif-badge" id="notif-badge">{{ $solicitudesCount }}</span>
                    @endif
                </button>
                <div class="profile-avatar">
                    <span class="avatar-emoji">😊</span>
                </div>
            </div>
        </div>

        <div class="search-container">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" placeholder="Buscar perros en adopción...">
        </div>
    </header>

    <main class="main-content">
        <section class="stats-section">
            <h2 class="section-title">Impacto del programa</h2>
            <div class="stats-grid">
                <div class="stat-card stat-rescued">
                    <div class="stat-number">247</div>
                    <div class="stat-label">🐾 Rescatados<br>este año</div>
                </div>
                <div class="stat-card stat-adopted">
                    <div class="stat-number">183</div>
                    <div class="stat-label">🏠 Adoptados</div>
                </div>
            </div>
        </section>

        <section class="pets-section">
            <h2 class="section-title">Disponibles para adopción</h2>
            <div class="pets-grid" id="petsGrid">
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-medium);">
                    Cargando mascotas disponibles...
                </div>
            </div>
        </section>
    </main>

    <nav class="bottom-nav">
        <button class="nav-item active">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Inicio</span>
        </button>
        <button class="nav-item" id="btn-explorer">
            <span class="nav-icon">🔍</span>
            <span class="nav-label">Explorar</span>
        </button>
        <button class="nav-item" id="btn-reportar">
            <span class="nav-icon">📢</span>
            <span class="nav-label">Reportar</span>
        </button>
        <button class="nav-item" id="btn-guardados">
            <span class="nav-icon">❤️</span>
            <span class="nav-label">Guardados</span>
        </button>
        <button class="nav-item" id="btn-publicar">
            <span class="nav-icon">➕</span>
            <span class="nav-label">Publicar</span>
        </button>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="nav-item" style="background: none; border: none; cursor: pointer;">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Salir</span>
            </button>
        </form>
    </nav>

    <div id="modal-favoritos" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 style="color: #634832;">🐾 Mis Favoritos</h2>
            <hr>
            <div id="lista-favoritos">
                <p style="text-align: center; color: #888; padding: 20px;">
                    Aún no has guardado ninguna mascota. <br>
                    ¡Explora y dales amor! ❤️
                </p>
            </div>
        </div>
    </div>

    <div id="modal-reportar" class="modal">
        <div class="modal-content">
            <span class="close-button" id="close-reportar">&times;</span>
            <h2 style="color: #634832;">📢 ¿Qué deseas reportar?</h2>
            <p>Tu reporte puede salvar una vida.</p>
            <div class="report-options">
                <button class="report-btn lost">🔍 Perdí una mascota</button>
                <button class="report-btn emergency">🚨 Emergencia</button>
            </div>
        </div>
    </div>

    <div id="modal-notificaciones" class="modal">
        <div class="modal-content modal-notif-content">
            <span class="close-button" id="close-notificaciones">&times;</span>
            <h2 style="color: #634832;">🔔 Solicitudes de adopción</h2>
            <hr>
            <div id="lista-solicitudes">
                <p style="text-align: center; color: #888; padding: 20px;">Cargando solicitudes...</p>
            </div>
        </div>
    </div>

    <div id="modal-publicar" class="modal">
        <div class="modal-content modal-publicar-content">
            <span class="close-button" id="close-publicar">&times;</span>
            <h2 style="color: #634832;">🐾 Publicar mascota</h2>
            <p style="color: var(--text-medium); font-size: 14px; margin-bottom: 16px;">Completa los datos para ayudar a encontrar un hogar</p>

            <div class="species-tabs">
                <button class="species-tab active" data-species="Perro">🐕 Perro</button>
                <button class="species-tab" data-species="Gato">🐈 Gato</button>
            </div>

            <form id="form-publicar" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="species" id="input-species" value="Perro">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="name" class="form-input" required placeholder="Ej: Max">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Raza *</label>
                        <input type="text" name="breed" class="form-input" list="breeds-list" required placeholder="Escribe o selecciona">
                        <datalist id="breeds-list"></datalist>
                    </div>

                    <div class="form-group form-group-row">
                        <div class="form-subgroup">
                            <label class="form-label">Edad *</label>
                            <input type="number" name="age" class="form-input" required min="0" max="50" placeholder="0">
                        </div>
                        <div class="form-subgroup">
                            <label class="form-label">Unidad</label>
                            <select name="age_unit" class="form-input">
                                <option value="años">Años</option>
                                <option value="meses">Meses</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Género *</label>
                        <select name="gender" class="form-input" required>
                            <option value="">Seleccionar</option>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ciudad *</label>
                        <input type="text" name="city" class="form-input" required placeholder="Ej: Medellín">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tamaño *</label>
                        <select name="size" class="form-input" required>
                            <option value="">Seleccionar</option>
                            <option value="Pequeño">Pequeño</option>
                            <option value="Mediano">Mediano</option>
                            <option value="Grande">Grande</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" name="weight" class="form-input" min="0" max="200" step="0.1" placeholder="Ej: 15">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-input" placeholder="Ej: Marrón claro">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Teléfono de contacto</label>
                        <input type="text" name="phone" class="form-input" placeholder="Ej: 300 123 4567">
                    </div>
                </div>

                <div class="form-checkboxes">
                    <label class="checkbox-label">
                        <input type="checkbox" name="vacunado" value="1">
                        <span>✓ Vacunado</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="castrado" value="1">
                        <span>✂️ Castrado</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-input form-textarea" placeholder="Cuenta su historia, personalidad, lo que necesita..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto del animal</label>
                    <div class="file-upload">
                        <input type="file" name="image" id="pet-image-input" accept="image/jpeg,image/png,image/jpg,image/webp">
                        <div class="file-upload-placeholder" id="file-upload-placeholder">
                            <span class="file-upload-icon">📸</span>
                            <span class="file-upload-text">Haz clic para subir una foto</span>
                            <span class="file-upload-hint">JPEG, PNG o WebP · Máx 5MB</span>
                        </div>
                        <img id="image-preview" class="image-preview" style="display: none;">
                        <button type="button" id="btn-remove-image" class="btn-remove-image" style="display: none;">✕</button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    🐾 Publicar mascota
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('home.js') }}"></script>
</body>
</html>
