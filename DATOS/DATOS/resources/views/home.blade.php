<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro / Login - Adopción de Mascotas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
</head>

<body>
    <main>

        @if($logueado)
            <div style="text-align: center; padding: 12px; background: #e8f5e9; border-radius: 12px; margin-bottom: 16px; max-width: 500px; margin-left: auto; margin-right: auto;">
                <span style="font-size: 14px; color: #2e7d32;">
                    Ya iniciaste sesión como <strong>{{ Auth::user()->name }}</strong>
                </span>
                <a href="{{ route('dashboard') }}" style="display: inline-block; margin-left: 10px; padding: 6px 16px; background: #2e7d32; color: white; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    Entrar al inicio
                </a>
            </div>
        @endif

        <div class="container" id="container">
            <div class="form-container sign-up-container">
                <form action="{{ route('usuario.registrar') }}" method="POST">
                    @csrf

                    <div class="paw-trail">
                        <i class="fa-solid fa-paw huella"></i>
                        <i class="fa-solid fa-paw huella"></i>
                        <i class="fa-solid fa-paw huella"></i>
                    </div>

                    @error('name')
                        <div class="alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('email')
                        <div class="alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('password')
                        <div class="alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <h2>Crear Cuenta</h2>
                    <h3>usa tu correo para registrarte</h3>

                    <div class="password-wrapper">
                        <input type="text" placeholder="Nombre" name="name" required />
                        <i class="fa-solid fa-user toggle-pass" style="cursor: default;"></i>
                    </div>

                    <div class="password-wrapper">
                        <input type="email" placeholder="Email" name="email" autocomplete="off" required />
                        <i class="fa-solid fa-envelope toggle-pass" style="cursor: default;"></i>
                    </div>

                    <div class="password-wrapper">
                        <input type="password" placeholder="Contraseña" name="password" required />
                        <i class="fa-solid fa-eye toggle-pass" onclick="togglePassword(this)"></i>
                    </div>

                    <div class="password-wrapper">
                        <input type="password" placeholder="Confirmar contraseña" name="password_confirmation" required />
                        <i class="fa-solid fa-eye toggle-pass" onclick="togglePassword(this)"></i>
                    </div>

                    <div style="margin-bottom: 16px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-dark); display: block; margin-bottom: 8px;">Tipo de cuenta</label>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <label style="flex: 1; min-width: 80px; padding: 8px; border: 2px solid var(--bg-light); border-radius: var(--radius-sm); text-align: center; cursor: pointer; font-size: 13px; transition: all 0.2s;" class="role-option" onclick="seleccionarRol(this, 'normal')">
                                <input type="radio" name="role" value="normal" checked hidden>
                                😊 Normal
                            </label>
                            <label style="flex: 1; min-width: 80px; padding: 8px; border: 2px solid var(--bg-light); border-radius: var(--radius-sm); text-align: center; cursor: pointer; font-size: 13px; transition: all 0.2s;" class="role-option" onclick="seleccionarRol(this, 'rescatista')">
                                <input type="radio" name="role" value="rescatista" hidden>
                                🦸 Rescatista
                            </label>
                            <label style="flex: 1; min-width: 80px; padding: 8px; border: 2px solid var(--bg-light); border-radius: var(--radius-sm); text-align: center; cursor: pointer; font-size: 13px; transition: all 0.2s;" class="role-option" onclick="seleccionarRol(this, 'refugio')">
                                <input type="radio" name="role" value="refugio" hidden>
                                🏠 Refugio
                            </label>
                            <label style="flex: 1; min-width: 80px; padding: 8px; border: 2px solid var(--bg-light); border-radius: var(--radius-sm); text-align: center; cursor: pointer; font-size: 13px; transition: all 0.2s;" class="role-option" onclick="seleccionarRol(this, 'veterinaria')">
                                <input type="radio" name="role" value="veterinaria" hidden>
                                💉 Veterinaria
                            </label>
                        </div>
                    </div>

                    <div id="role-extras" style="display: none; margin-bottom: 16px; text-align: left;">
                        <div class="form-group">
                            <label class="form-label">Nombre del lugar</label>
                            <input type="text" name="nombre_lugar" class="form-input" placeholder="Ej: Refugio La Perla">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-input" placeholder="Ej: Cra 50 # 45-20, Medellín">
                        </div>
                    </div>

                    <button type="submit">REGISTRARME</button>
                </form>
            </div>

            <div class="form-container sign-in-container">
                <form action="{{ route('usuario.login') }}" method="POST">
                    @csrf
                    @if(session('success'))
                        <div class="alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @error('email')
                        <div class="alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="paw-trail">
                        <i class="fa-solid fa-paw huella"></i>
                        <i class="fa-solid fa-paw huella"></i>
                        <i class="fa-solid fa-paw huella"></i>
                    </div>

                    <h2>Inicia Sesión</h2>
                    <h4>O crea una cuenta</h4>

                    <div class="password-wrapper">
                        <input type="email" placeholder="Email" name="email" id="email_login" autocomplete="off" required />
                        <i class="fa-solid fa-envelope toggle-pass" style="cursor: default;"></i>
                    </div>

                    <div class="password-wrapper">
                        <input type="password" placeholder="Contraseña" name="password" required />
                        <i class="fa-solid fa-eye toggle-pass" onclick="togglePassword(this)"></i>
                    </div>

                    <button type="submit">INICIAR SESIÓN</button>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h2>BIENVENIDO</h2>
                        <p1>Para mantenerte conectado, por favor inicia sesión con tus datos personales</p1>
                        <button class="ghost" id="signIn">INICIAR SESIÓN</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h2>Hola, Amigo!</h2>
                        <p1>Ingresa tus datos y comienza tu experiencia con nosotros</p1>
                        <button class="ghost" id="signUp">REGISTRARSE</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function seleccionarRol(elemento, rol) {
            document.querySelectorAll('.role-option').forEach(el => {
                el.style.borderColor = 'var(--bg-light)';
                el.style.background = 'transparent';
            });
            elemento.style.borderColor = '#e8744f';
            elemento.style.background = 'rgba(232, 116, 79, 0.08)';

            const extras = document.getElementById('role-extras');
            if (rol === 'refugio' || rol === 'veterinaria') {
                extras.style.display = 'block';
            } else {
                extras.style.display = 'none';
            }
        }
    </script>
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>
