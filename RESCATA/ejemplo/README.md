# Aplicación de Adopción de Mascotas

Una aplicación web responsive para facilitar la adopción de mascotas, con sistema de registro e inicio de sesión.

## 🎨 Paleta de Colores

La aplicación utiliza una paleta amigable y acogedora:

- **Naranja Principal**: `#e8744f` - Color cálido que transmite energía y amabilidad
- **Verde Secundario**: `#6ba587` - Representa naturaleza y cuidado
- **Morado Acento**: `#a794d4` - Añade diversidad visual
- **Rosa Acento**: `#f6a5b8` - Toque dulce y tierno
- **Amarillo Acento**: `#ffd166` - Alegría y optimismo
- **Crema Fondo**: `#fef8f3` - Base suave y acogedora

## 📁 Estructura de Archivos

```
ejemplo/
├── index.html          # Página de login/registro
├── home.html           # Dashboard principal
├── pet-detail.html     # Página de detalles de mascota
├── global.css          # Estilos globales y variables CSS
├── styles.css          # Estilos específicos de login
├── home.css            # Estilos específicos del dashboard
├── pet-detail.css      # Estilos específicos de detalles
├── script.js           # Lógica de login/registro
├── home.js             # Lógica del dashboard
├── pet-detail.js       # Lógica de detalles
├── pets.json           # Base de datos de mascotas
└── README.md           # Este archivo
```

## 🚀 Características

### Página de Login/Registro (index.html)
- Diseño con paneles deslizantes
- Formularios de registro e inicio de sesión
- Responsive para móvil, tablet y desktop
- Integración con redes sociales (placeholder)
- Transiciones suaves

### Dashboard Principal (home.html)
- Header personalizado con saludo dinámico
- Barra de búsqueda funcional
- Tarjetas de estadísticas (rescatados/adoptados)
- Grid de mascotas cargadas dinámicamente desde JSON
- Imágenes reales de mascotas con fallback a emojis
- Click en tarjetas para ver detalles
- Navegación inferior con iconos
- Completamente responsive

### Página de Detalles (pet-detail.html)
- Carga dinámica de datos desde JSON
- Imagen grande de la mascota
- Información completa: edad, raza, género, ciudad
- Badges de características (vacunado, castrado, etc.)
- Información de peso, tamaño y refugio
- Descripción detallada
- Sistema de favoritos (LocalStorage)
- Botón de adopción
- Navegación completa

## 💻 Uso

### Modo Local

1. Abrir `index.html` en el navegador
2. Registrarse o iniciar sesión (simulado)
3. Serás redirigido a `home.html`
4. Haz clic en cualquier mascota para ver sus detalles
5. Usa el botón de favoritos (corazón) para guardar mascotas
6. Usa la barra de búsqueda para filtrar por nombre, raza o ciudad

### Con Servidor Local

```powershell
# Python 3
python -m http.server 8000

# Luego abrir http://localhost:8000
```

## 📱 Responsive Design

- **Desktop (>1024px)**: Layout completo con todas las características
- **Tablet (801-1024px)**: Diseño optimizado con grid ajustado
- **Móvil (≤800px)**: 
  - Paneles apilados en login
  - Grid de 2 columnas para mascotas
  - Navegación inferior fija
  - Inputs y botones más grandes para touch

## 🎯 Próximas Mejoras

- [ ] Integración con backend real
- [x] Sistema de favoritos funcional
- [x] Página de detalles de mascota
- [x] Filtros de búsqueda funcionales
- [ ] Sistema de notificaciones
- [ ] Perfil de usuario editable
- [x] Carga dinámica desde JSON
- [ ] Formulario de solicitud de adopción
- [ ] Filtros avanzados (especie, tamaño, edad)
- [ ] Página de mascotas favoritas

## 🛠️ Tecnologías

- HTML5
- CSS3 (Custom Properties, Flexbox, Grid)
- JavaScript Vanilla (ES6+, Fetch API, Async/Await)
- LocalStorage para persistencia básica
- JSON para base de datos de mascotas

## 📊 Estructura del JSON

El archivo `pets.json` contiene un array de mascotas con la siguiente estructura:

```json
{
  "id": 1,
  "name": "Nombre",
  "species": "Perro/Gato",
  "breed": "Raza",
  "age": 2,
  "ageUnit": "años/meses",
  "gender": "Macho/Hembra",
  "city": "Ciudad",
  "weight": "15 kg",
  "size": "Pequeño/Mediano/Grande",
  "shelter": "Nombre del refugio",
  "status": "Disponible",
  "traits": [...],
  "description": "Descripción...",
  "image": "URL de la imagen",
  "emoji": "🐕",
  "color": "#hexcolor"
}
```

Para agregar más mascotas, simplemente añade más objetos al array `pets` en `pets.json`.

## 📝 Notas

El sistema de autenticación es simulado. Los datos se almacenan temporalmente en localStorage y se pierden al limpiar el navegador.
