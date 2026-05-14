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

        <div class="container" id="container">
            <div class="form-container sign-up-container">
                <form action="{{ route('usuario.registrar') }}" method="POST">
                    @csrf

                    <div class="paw-trail">
                        <i class="fa-solid fa-paw huella"></i>
                        <i class="fa-solid fa-paw huella"></i>
                        <i class="fa-solid fa-paw huella"></i>
                    </div>

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
                    @if(session('success'))
                        <div class="alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

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

    <script src="{{ asset('script.js') }}"></script>
</body>
</html>
