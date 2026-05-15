<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $user->name }} - Adopción de Mascotas</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .org-header {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
            padding: var(--spacing-xl) var(--spacing-md);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            box-shadow: var(--shadow-md);
            margin-bottom: var(--spacing-lg);
            text-align: center;
            color: white;
        }
        .org-icon {
            font-size: 48px;
            margin-bottom: 8px;
        }
        .org-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .org-role {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 12px;
        }
        .org-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            opacity: 0.9;
        }
        .org-info span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>
<body>
    <div class="org-header">
        <div class="org-icon">{{ $user->role === 'veterinaria' ? '💉' : '🏠' }}</div>
        <div class="org-name">{{ $user->name }}</div>
        <div class="org-role">{{ $user->role === 'veterinaria' ? 'Veterinaria' : 'Refugio' }}</div>
        <div class="org-info">
            @if($user->direccion)
                <span>📍 {{ $user->direccion }}</span>
            @endif
            @if($user->phone)
                <span>📞 {{ $user->phone }}</span>
            @endif
            <span>🐾 {{ $pets->count() }} mascotas disponibles</span>
        </div>
    </div>

    <main class="main-content">
        <section class="pets-section">
            @if($pets->count() > 0)
                <h2 class="section-title">Mascotas en adopción</h2>
                <div class="pets-grid" id="petsGrid">
                    @foreach($pets as $pet)
                        <div class="pet-card" onclick="window.location.href='/mascota/{{ $pet->id }}'">
                            <div class="pet-image-container" style="background: linear-gradient(135deg, {{ $pet->color ?? '#ffeaa7' }}, {{ $pet->color ?? '#fdcb6e' }}dd)">
                                @if($pet->image)
                                    <img src="{{ $pet->image }}" alt="{{ $pet->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                                    <span class="pet-emoji" style="display: none; font-size: 48px; width: 100%; height: 100%; align-items: center; justify-content: center;">{{ $pet->emoji ?? '🐾' }}</span>
                                @else
                                    <span class="pet-emoji" style="font-size: 48px;">🐾</span>
                                @endif
                            </div>
                            <div class="pet-info">
                                <h3 class="pet-name">{{ $pet->name }}</h3>
                                <p class="pet-details">{{ $pet->age }} {{ $pet->age_unit }} · {{ $pet->city ?? 'Sin ubicación' }}</p>
                                <span class="badge badge-success">{{ $pet->status }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <p style="font-size: 64px; margin-bottom: 16px;">🐾</p>
                    <h2 style="color: var(--text-dark); margin-bottom: 8px;">No hay mascotas disponibles</h2>
                    <p style="color: var(--text-medium); font-size: 14px;">Este lugar aún no ha publicado mascotas</p>
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
        <button class="nav-item" onclick="window.location.href='{{ route('favoritos.page') }}'">
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
