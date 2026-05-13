<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio - Adopción de Mascotas</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">  
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="greeting">
                <span class="greeting-text">Hola, buenas tardes 👋</span>
                <h1 class="user-name" id="userName">{{ Auth::user()->name }}</h1>
            </div>
            <div class="profile-avatar">
                <span class="avatar-emoji">😊</span>
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

    <script src="{{ asset('home.js') }}"></script>
</body>
</html>