<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Error del servidor</title>
    <link rel="stylesheet" href="{{ asset('global.css') }}">
    <link rel="icon" href="{{ asset('assets/logo_sin_fon.png') }}" type="image/png">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            text-align: center;
        }
        .error-container {
            max-width: 400px;
        }
        .error-code {
            font-size: 96px;
            font-weight: 700;
            color: var(--primary-orange);
            line-height: 1;
            margin-bottom: 16px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .error-text {
            font-size: 14px;
            color: var(--text-medium);
            margin-bottom: 24px;
        }
        .error-emoji {
            font-size: 64px;
            margin-bottom: 16px;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-orange-light));
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-emoji">😿</div>
        <div class="error-code">500</div>
        <div class="error-title">Algo salió mal</div>
        <p class="error-text">Estamos teniendo problemas técnicos. Ya lo estamos revisando para que los animalitos encuentren su hogar.</p>
        <a href="{{ route('dashboard') }}" class="btn-home">🏠 Volver al inicio</a>
    </div>
</body>
</html>
