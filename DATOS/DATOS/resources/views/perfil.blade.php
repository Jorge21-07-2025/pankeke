<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mi Perfil - Adopción de Mascotas</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="header" style="margin-bottom: 0; border-radius: 0 0 var(--radius-xl) var(--radius-xl);">
        <div class="header-content">
            <div class="greeting">
                <span class="greeting-text">👤 Mi Perfil</span>
                <h1 class="user-name">{{ $user->name }}</h1>
            </div>
            <div class="profile-avatar" style="position: relative;">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    <span class="avatar-emoji" style="font-size: 28px;">😊</span>
                @endif
            </div>
        </div>
    </header>

    <main class="main-content" style="padding-top: 20px;">
        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        <section style="margin-bottom: 24px;">
            <h2 class="section-title">Mis datos</h2>
            <div class="card" style="padding: 20px;">
                <p style="margin-bottom: 8px;"><strong>Nombre:</strong> {{ $user->name }}</p>
                <p style="margin-bottom: 8px;"><strong>Email:</strong> {{ $user->email }}</p>
                <p style="margin-bottom: 0;"><strong>Teléfono:</strong> {{ $user->phone ?? 'No registrado' }}</p>
            </div>

            <button id="btn-editar-perfil" class="btn-submit" style="margin-top: 12px; padding: 12px;">
                ✏️ Editar perfil
            </button>
        </section>

        <section style="margin-bottom: 24px;">
            <h2 class="section-title">Mis mascotas publicadas</h2>
            @if($user->pets->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($user->pets as $pet)
                        <div class="card" style="padding: 14px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 16px;">{{ $pet->name }}</strong>
                                <span style="font-size: 13px; color: var(--text-medium);"> · {{ $pet->species }} · {{ $pet->breed }}</span>
                                <br>
                                <span class="badge badge-success" style="margin-top: 4px;">{{ $pet->status }}</span>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('mascotas.show', $pet->id) }}" class="btn" style="padding: 6px 14px; font-size: 12px; background: var(--bg-light); color: var(--text-dark); text-decoration: none; border-radius: var(--radius-sm);">Ver</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-medium); padding: 16px 0;">Aún no has publicado ninguna mascota.</p>
            @endif
        </section>

        <section style="margin-bottom: 24px;">
            <h2 class="section-title">Mis solicitudes de adopción</h2>
            @php
                $myRequests = $user->adoptionRequests;
            @endphp
            @if($myRequests->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($myRequests as $req)
                        <div class="card" style="padding: 14px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 16px;">🐾 {{ $req->pet->name ?? 'Mascota eliminada' }}</strong>
                                    <span style="font-size: 13px; color: var(--text-medium);">
                                        · Solicitado el {{ $req->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <span class="badge" style="background: 
                                    @if($req->status === 'en_proceso') var(--accent-yellow); color: var(--text-dark);
                                    @elseif($req->status === 'aprobado') var(--secondary-green-light); color: var(--secondary-green-dark);
                                    @else var(--bg-light); color: var(--text-medium);
                                    @endif
                                ;">
                                    @if($req->status === 'en_proceso') ⏳ En proceso
                                    @elseif($req->status === 'aprobado') ✅ Aprobado
                                    @else ❌ Rechazado
                                    @endif
                                </span>
                            </div>
                            @if($req->message)
                                <p style="font-size: 13px; color: var(--text-medium); margin-top: 8px; font-style: italic;">
                                    "{{ $req->message }}"
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-medium); padding: 16px 0;">No has solicitado adoptar ninguna mascota aún.</p>
            @endif
        </section>

        <div style="text-align: center; padding: 20px 0 80px;">
            <button onclick="document.getElementById('form-logout-perfil').submit();" style="background: none; border: 2px solid #e74c3c; color: #e74c3c; padding: 10px 24px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                🚪 Cerrar sesión
            </button>
        </div>
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
        <button class="nav-item">
            <span class="nav-icon">➕</span>
            <span class="nav-label">Publicar</span>
        </button>
        <button class="nav-item active">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Perfil</span>
        </button>
    </nav>

    <div id="modal-editar-perfil" class="modal" style="display: none;">
        <div class="modal-content modal-publicar-content">
            <span class="close-button" id="close-editar-perfil">&times;</span>
            <h2 style="color: #634832;">✏️ Editar perfil</h2>
            <form action="{{ route('perfil.actualizar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-input" value="{{ $user->phone }}" placeholder="Ej: 300 123 4567">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto de perfil</label>
                    <div class="file-upload">
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp">
                        <div class="file-upload-placeholder">
                            <span class="file-upload-icon">📸</span>
                            <span class="file-upload-text">Cambiar foto</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-submit">💾 Guardar cambios</button>
            </form>
        </div>
    </div>

    <form id="form-logout-perfil" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="{{ asset('home.js') }}"></script>
</body>
</html>
