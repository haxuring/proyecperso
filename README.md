# LoginRegister — Sistema de login y registro con PHP, MySQL y JavaScript

Proyecto personal completo de autenticación de usuarios: registro, inicio de sesión,
panel privado, perfil editable y página de contacto. Construido con PHP nativo (PDO),
MySQL, JavaScript vanilla y CSS moderno sin frameworks.

## Características

### Backend (PHP + MySQL)

- Registro con validación en el servidor y contraseñas cifradas (`password_hash()` / `password_verify()`).
- Sentencias preparadas en el 100 % de las consultas (protección frente a inyección SQL).
- Tokens **CSRF** generados por sesión en todos los formularios POST.
- Protección contra **fuerza bruta**: bloqueo de 5 minutos tras 5 intentos fallidos.
- Sesiones PHP con regeneración de ID y opción «recuérdame» de 30 días.
- Perfil editable: nombre, correo (con comprobación de duplicados) y contraseña
  (verificando siempre la actual).
- Formulario de contacto que guarda los mensajes en la base de datos.
- Registro del último inicio de sesión de cada usuario.

### Frontend (JavaScript vanilla)

- Menú responsive tipo hamburguesa accesible (`aria-expanded`, cierre con `Esc`).
- **Tema claro/oscuro** persistente con `localStorage`, respetando la preferencia del sistema.
- Medidor de fuerza de contraseña en tiempo real.
- Mostrar/ocultar contraseña en todos los campos sensibles.
- Validación de coincidencia de contraseñas antes de enviar el formulario.
- Avisos flotantes (toasts) auto-cerrables para los mensajes flash del servidor.
- Animaciones al hacer scroll con `IntersectionObserver`.
- Contador de caracteres en el formulario de contacto.

### HTML + CSS

- HTML5 semántico: un único `<h1>` por página y jerarquía correcta de encabezados.
- Etiquetas semánticas sin abuso de `<div>`: `header`, `nav`, `main`, `section`,
  `article`, `aside`, `details`, `address`, `dl`, `progress`…
- Metadatos SEO por página: título y descripción únicos, Open Graph básico.
- Accesibilidad: enlace «saltar al contenido», `aria-current`, `aria-live`, foco visible.
- Sistema de diseño con variables CSS, tipografía fluida con `clamp()`,
  rejillas automáticas y soporte de `prefers-reduced-motion`.

## Tecnologías

| Tecnología   | Uso                                       |
| ------------ | ----------------------------------------- |
| PHP 8+       | Lógica del servidor, sesiones y seguridad |
| MySQL        | Usuarios y mensajes de contacto           |
| PDO          | Acceso a la base de datos preparado       |
| JavaScript   | Interfaz dinámica sin librerías           |
| HTML5 / CSS3 | Marcado semántico y diseño responsive     |

## Estructura del proyecto

```
login-register-php/
├── assets/
│   ├── css/styles.css          # Sistema de diseño completo (claro/oscuro)
│   └── js/main.js              # Toda la interacción del cliente
├── config/
│   └── database.example.php    # Plantilla de conexión (el real va en .gitignore)
├── includes/
│   ├── funciones.php           # Helpers: escape, CSRF, flash, fechas
│   ├── header.php              # Cabecera común: SEO, nav, toasts
│   └── footer.php              # Pie común y carga del JS
├── sql/
│   └── database.sql            # Esquema completo de la base de datos
├── index.php                   # Portada pública
├── register.php                # Registro con medidor de contraseña
├── login.php                   # Login con bloqueo anti fuerza bruta
├── dashboard.php               # Panel privado con estadísticas
├── perfil.php                  # Edición de datos y contraseña
├── contacto.php                # Formulario de contacto (guarda en BD)
├── acerca-de.php               # Página "Acerca de"
├── faq.php                     # Preguntas frecuentes (<details>)
├── 404.php                     # Página de error personalizada
├── logout.php                  # Cierre de sesión seguro
└── README.md
```

## Puesta en marcha

### 1. Clona el repositorio

```bash
git clone https://github.com/tu-usuario/login-register-php.git
cd login-register-php
```

### 2. Importa la base de datos

Desde phpMyAdmin o por consola:

```bash
mysql -u root -p < sql/database.sql
```

> Si ya tenías una versión anterior del proyecto, vuelve a importar el SQL:
> el esquema cambió (columnas `creado_en` y `ultimo_acceso`, tabla nueva `mensajes`).

### 3. Configura la conexión

```bash
cp config/database.example.php config/database.php
```

Edita ese archivo con tus credenciales. Está excluido por `.gitignore`, así que
nunca se subirá al repositorio.

### 4. Arranca el servidor

```bash
php -S localhost:8000
```

Abre `http://localhost:8000` en tu navegador.

## Seguridad implementada

- `password_hash()` (bcrypt) y `password_verify()` para todas las contraseñas.
- Sentencias preparadas con parámetros nombrados en todas las consultas.
- Tokens CSRF validados con `hash_equals()` en cada envío POST.
- Bloqueo temporal del login tras intentos fallidos repetidos.
- `htmlspecialchars()` en toda salida dinámica (prevención de XSS).
- `session_regenerate_id(true)` tras login y registro (fijación de sesión).
- Mensajes de error genéricos (no se revela si un correo está registrado).
- Cabeceras HTTP de seguridad (`nosniff`, `X-Frame-Options`, `Referrer-Policy`).
- `.htaccess` que bloquea el acceso directo a archivos de configuración.

## Personalización rápida

- **Colores y tema:** edita las variables al inicio de `assets/css/styles.css`.
- **Enlaces de navegación:** array `$enlaces_nav` en `includes/header.php`.
- **Datos de contacto:** cambia los enlaces en `contacto.php` y el pie de página.
- **Página 404 en Apache:** ajusta la ruta en `.htaccess` si el proyecto vive
  dentro de un subdirectorio.

## Mejoras futuras

- Verificación del correo electrónico al registrarse.
- Recuperación de contraseña por email.
- Panel de administración para leer los mensajes de contacto.
- Limitación de intentos persistente en base de datos (no solo en sesión).

## Licencia

MIT — siéntete libre de usarlo, estudiarlo y modificarlo.
