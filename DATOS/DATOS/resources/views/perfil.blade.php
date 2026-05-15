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

                @if($user->role === 'refugio' || $user->role === 'veterinaria')
                    <p style="margin-bottom: 0;"><strong>Dirección:</strong> {{ $user->direccion ?? 'No registrada' }}</p>
                @endif
            </div>

            <div class="card" style="padding: 20px; margin-top: 12px; background: var(--bg-light);">
                <p style="margin-bottom: 4px;">
                    <strong>Tipo de cuenta:</strong>
                    @if($user->role === 'normal') 😊 Normal
                    @elseif($user->role === 'rescatista') 🦸 Rescatista
                    @elseif($user->role === 'refugio') 🏠 Refugio
                    @elseif($user->role === 'veterinaria') 💉 Veterinaria
                    @endif
                </p>
                @if($user->role === 'rescatista' && $user->refugio)
                    <p style="margin-bottom: 0; font-size: 14px; color: var(--text-medium);">🏠 Vinculado a: <strong>{{ $user->refugio }}</strong></p>
                @endif
            </div>

            @if($user->role === 'normal')
                <form action="{{ route('volverse.rescatista') }}" method="POST" style="margin-top: 12px;">
                    @csrf
                    <button type="submit" class="btn-submit" style="padding: 12px; background: linear-gradient(135deg, #6ba587, #8bc9a8);">
                        🦸 Quiero ser rescatista
                    </button>
                </form>
            @endif

            @if($user->role === 'rescatista')
                <form id="form-actualizar-refugio" method="POST" action="{{ route('perfil.actualizar') }}" style="margin-top: 12px;">
                    @csrf
                    <div style="display: flex; gap: 8px; align-items: flex-start;">
                        <div style="flex: 1;">
                            <label style="font-size: 12px; font-weight: 600; color: var(--text-medium); display: block; margin-bottom: 4px;">¿Estás vinculado a un refugio?</label>
                            <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                                <label style="font-size: 13px; display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                    <input type="radio" name="tiene_refugio" value="si" {{ $user->refugio ? 'checked' : '' }} onchange="document.getElementById('campo-refugio').style.display='block'"> Sí
                                </label>
                                <label style="font-size: 13px; display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                    <input type="radio" name="tiene_refugio" value="no" {{ !$user->refugio ? 'checked' : '' }} onchange="document.getElementById('campo-refugio').style.display='none'"> No
                                </label>
                            </div>
                            <div id="campo-refugio" style="{{ $user->refugio ? '' : 'display: none;' }}">
                                <input type="text" name="refugio" class="form-input" placeholder="Nombre del refugio" value="{{ $user->refugio ?? '' }}">
                            </div>
                        </div>
                        <button type="submit" class="btn-submit" style="width: auto; padding: 10px 20px; margin-top: 22px;">Guardar</button>
                    </div>
                </form>
            @endif

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
                                <button type="button" class="btn-editar-pet" data-pet='@json($pet)' style="padding: 6px 14px; font-size: 12px; background: var(--accent-yellow); color: var(--text-dark); border: none; border-radius: var(--radius-sm); cursor: pointer;">✏️ Editar</button>
                                <button type="button" class="btn-eliminar-pet" data-id="{{ $pet->id }}" data-name="{{ $pet->name }}" style="padding: 6px 14px; font-size: 12px; background: #e74c3c; color: white; border: none; border-radius: var(--radius-sm); cursor: pointer;">🗑️</button>
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
        <button class="nav-item active" onclick="irA('perfil')">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Perfil</span>
        </button>
    </nav>

    <div id="modal-editar-mascota" class="modal" style="display: none;">
        <div class="modal-content modal-publicar-content">
            <span class="close-button" id="close-editar-mascota">&times;</span>
            <h2 style="color: #634832;">✏️ Editar mascota</h2>
            <form id="form-editar-mascota" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-pet-id">
                <input type="hidden" name="species" id="edit-pet-species" value="Perro">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="name" id="edit-pet-name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Raza *</label>
                        <input type="text" name="breed" id="edit-pet-breed" class="form-input" required>
                    </div>
                    <div class="form-group form-group-row">
                        <div class="form-subgroup">
                            <label class="form-label">Edad *</label>
                            <input type="number" name="age" id="edit-pet-age" class="form-input" required min="0" max="50">
                        </div>
                        <div class="form-subgroup">
                            <label class="form-label">Unidad</label>
                            <select name="age_unit" id="edit-pet-age-unit" class="form-input">
                                <option value="años">Años</option>
                                <option value="meses">Meses</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Género *</label>
                        <select name="gender" id="edit-pet-gender" class="form-input" required>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ciudad *</label>
                        <input type="text" name="city" id="edit-pet-city" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tamaño *</label>
                        <select name="size" id="edit-pet-size" class="form-input" required>
                            <option value="Pequeño">Pequeño</option>
                            <option value="Mediano">Mediano</option>
                            <option value="Grande">Grande</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" name="weight" id="edit-pet-weight" class="form-input" min="0" max="200" step="0.1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" id="edit-pet-color" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" id="edit-pet-phone" class="form-input">
                    </div>
                </div>

                <div class="form-checkboxes">
                    <label class="checkbox-label">
                        <input type="checkbox" name="vacunado" id="edit-pet-vacunado" value="1">
                        <span>✓ Vacunado</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="castrado" id="edit-pet-castrado" value="1">
                        <span>✂️ Castrado</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="sociable" id="edit-pet-sociable" value="1">
                        <span>😊 Sociable</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="entrenado" id="edit-pet-entrenado" value="1">
                        <span>🎓 Entrenado</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" id="edit-pet-description" class="form-input form-textarea"></textarea>
                </div>

                <button type="submit" class="btn-submit">💾 Guardar cambios</button>
            </form>
        </div>
    </div>

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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal-editar-mascota');
        const closeBtn = document.getElementById('close-editar-mascota');
        const form = document.getElementById('form-editar-mascota');

        document.querySelectorAll('.btn-editar-pet').forEach(btn => {
            btn.addEventListener('click', function() {
                const pet = JSON.parse(this.dataset.pet);

                document.getElementById('edit-pet-id').value = pet.id;
                document.getElementById('edit-pet-name').value = pet.name;
                document.getElementById('edit-pet-species').value = pet.species;
                document.getElementById('edit-pet-breed').value = pet.breed;
                document.getElementById('edit-pet-age').value = pet.age;
                document.getElementById('edit-pet-age-unit').value = pet.age_unit;
                document.getElementById('edit-pet-gender').value = pet.gender;
                document.getElementById('edit-pet-city').value = pet.city;
                document.getElementById('edit-pet-size').value = pet.size;

                const weight = pet.weight ? pet.weight.replace(' kg', '') : '';
                document.getElementById('edit-pet-weight').value = weight;
                document.getElementById('edit-pet-color').value = pet.color || '';
                document.getElementById('edit-pet-phone').value = pet.phone || '';
                document.getElementById('edit-pet-vacunado').checked = !!pet.vacunado;
                document.getElementById('edit-pet-castrado').checked = !!pet.castrado;
                document.getElementById('edit-pet-sociable').checked = !!pet.sociable;
                document.getElementById('edit-pet-entrenado').checked = !!pet.entrenado;
                document.getElementById('edit-pet-description').value = pet.description || '';

                modal.style.display = 'block';
            });
        });

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

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const petId = document.getElementById('edit-pet-id').value;
                const submitBtn = this.querySelector('.btn-submit');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Guardando...';

                try {
                    const formData = new FormData(this);
                    formData.append('_method', 'PUT');

                    const response = await fetch('/mascotas/' + petId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (data.success) {
                        modal.style.display = 'none';
                        alert('✅ ' + data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo actualizar'));
                    }
                } catch (error) {
                    alert('Error al conectar con el servidor');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = '💾 Guardar cambios';
                }
            });
        }

        document.querySelectorAll('.btn-eliminar-pet').forEach(btn => {
            btn.addEventListener('click', function() {
                const petId = this.dataset.id;
                const petName = this.dataset.name;

                if (!confirm('¿Seguro que quieres eliminar a ' + petName + '?')) return;

                fetch('/mascotas/' + petId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo eliminar'));
                    }
                })
                .catch(() => alert('Error al conectar con el servidor'));
            });
        });
    });
    </script>

    <script>
        const CURRENT_USER_ID = {{ Auth::id() }};
    </script>
    <script src="{{ asset('home.js') }}"></script>
</body>
</html>
