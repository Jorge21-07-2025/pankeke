<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mis Favoritos - Adopción de Mascotas</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="greeting">
                <h1 class="user-name">❤️ Mis Favoritos</h1>
            </div>
            <div class="profile-avatar">
                <span class="avatar-emoji">😊</span>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="pets-section">
            @if($favorites->count() > 0)
                <div class="pets-grid" id="petsGrid">
                    @foreach($favorites as $fav)
                        <div class="pet-card" onclick="window.location.href='/mascota/{{ $fav->pet->id }}'">
                            <div class="pet-image-container" style="background: linear-gradient(135deg, {{ $fav->pet->color ?? '#ffeaa7' }}, {{ $fav->pet->color ?? '#fdcb6e' }}dd)">
                                @if($fav->pet->image)
                                    <img src="{{ $fav->pet->image }}" alt="{{ $fav->pet->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                                    <span class="pet-emoji" style="display: none; font-size: 48px; width: 100%; height: 100%; align-items: center; justify-content: center;">{{ $fav->pet->emoji ?? '🐾' }}</span>
                                @else
                                    <span class="pet-emoji" style="font-size: 48px;">🐾</span>
                                @endif
                            </div>
                            <div class="pet-info">
                                <h3 class="pet-name">{{ $fav->pet->name }}</h3>
                                <p class="pet-details">{{ $fav->pet->age }} {{ $fav->pet->age_unit }} · {{ $fav->pet->city ?? 'Sin ubicación' }}</p>
                                <span class="badge badge-success">{{ $fav->pet->status }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <p style="font-size: 64px; margin-bottom: 16px;">❤️</p>
                    <h2 style="color: var(--text-dark); margin-bottom: 8px;">Aún no tienes favoritos</h2>
                    <p style="color: var(--text-medium); font-size: 14px; margin-bottom: 24px;">Explora mascotas y guarda tus favoritas tocando el corazón</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">🐾 Explorar mascotas</a>
                </div>
            @endif
        </section>
    </main>

    <nav class="bottom-nav">
        <button class="nav-item" onclick="window.location.href='{{ route('dashboard') }}'">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Inicio</span>
        </button>
        <button class="nav-item" onclick="window.location.href='/dashboard?accion=explorar'">
            <span class="nav-icon">🔍</span>
            <span class="nav-label">Explorar</span>
        </button>
        <button class="nav-item" onclick="window.location.href='/dashboard?accion=reportar'">
            <span class="nav-icon">📢</span>
            <span class="nav-label">Reportar</span>
        </button>
        <button class="nav-item active" onclick="window.location.href='{{ route('favoritos.page') }}'">
            <span class="nav-icon">❤️</span>
            <span class="nav-label">Guardados</span>
        </button>
        <button class="nav-item" onclick="window.location.href='/dashboard?accion=publicar'">
            <span class="nav-icon">➕</span>
            <span class="nav-label">Publicar</span>
        </button>
        <button class="nav-item" onclick="window.location.href='{{ route('perfil') }}'">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Perfil</span>
        </button>
    </nav>

    <script>
        const CURRENT_USER_ID = {{ Auth::id() }};
    </script>
</body>
</html>
