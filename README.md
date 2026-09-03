# AuthCore PHP — Sistema de Autenticación & Perfil de Usuario

Solución integral de autenticación y gestión de usuarios construida con **PHP 8 (PDO)**, **MySQL** y **JavaScript vanilla**. Enfocada en buenas prácticas de seguridad, accesibilidad y diseño moderno nativo sin dependencias externas.

## Aspectos Clave

### Backend & Seguridad
- Cifrado de contraseñas mediante `password_hash()` y `password_verify()`.
- Consultas preparadas en el 100 % de las interacciones con la base de datos (protección anti SQLi).
- Verificación estricta de tokens **CSRF** en cada petición `POST`.
- Rate limiting / Bloqueo temporal tras 5 intentos fallidos de inicio de sesión.
- Manejo seguro de sesiones (`session_regenerate_id`) y persistencia mediante tokens ("Recuérdame").
- Saneamiento sistemático de salidas con `htmlspecialchars()` para prevenir vulnerabilidades XSS.

### Frontend & Interfaz
- **Tema Dark/Light** automático y manual con persistencia en `localStorage`.
- Medidor de fuerza de contraseña e indicadores dinámicos en formularios.
- Componente de avisos flotantes (Toasts) para retroalimentación instantánea.
- Interfaz responsiva con CSS Grid/Flexbox y tipografía fluida con `clamp()`.
- HTML5 semántico con estándares de accesibilidad (atributos ARIA y navegación por teclado).

## Stack Tecnológico

| Tecnología | Rol |
| :--- | :--- |
| **PHP 8+** | Arquitectura del servidor, lógica de negocio y sesiones |
| **MySQL** | Persistencia de datos (Usuarios y Mensajes) |
| **PDO** | Capa de abstracción de base de datos segura |
| **JavaScript** | Manejo del DOM, validaciones y tema visual |
| **HTML5 / CSS3** | Estructura semántica y sistema de diseño sin frameworks |

## Estructura del Proyecto

```text
authcore-php/
├── assets/
│   ├── css/styles.css        # Variables de diseño y estilos globales
│   └── js/app.js             # Lógica de cliente y manipulación del DOM
├── config/
│   └── db.example.php        # Plantilla de credenciales de la BD
├── includes/
│   ├── helpers.php           # Funciones globales (CSRF, sanitización, flash)
│   ├── header.php            # Shell superior, SEO y navegación
│   └── footer.php            # Scripts y cierre de estructura
├── sql/
│   └── schema.sql            # Script de inicialización de tablas
├── index.php                 # Landing page principal
├── login.php                 # Acceso con protección anti fuerza bruta
├── register.php              # Registro de nuevos usuarios
├── dashboard.php             # Área privada de usuario
├── profile.php               # Gestión del perfil y credenciales
├── contact.php               # Módulo de mensajes directos
├── logout.php                # Destrucción segura de sesión
└── README.md
```

## Instalación & Configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/authcore-php.git
cd authcore-php
```

### 2. Cargar la base de datos
Importa el archivo `sql/schema.sql` desde tu cliente MySQL o CLI:
```bash
mysql -u tu_usuario -p tu_base_datos < sql/schema.sql
```

### 3. Configurar entorno
Copia la plantilla de configuración y ajusta tus credenciales locales:
```bash
cp config/db.example.php config/db.php
```

### 4. Iniciar el servidor
```bash
php -S localhost:8000
```
Accede a `http://localhost:8000` en tu navegador.

## Seguridad Implementada

- `password_hash()` (bcrypt) y `password_verify()` para contraseñas.
- Consultas preparadas PDO con parámetros nombrados.
- Tokens CSRF validados con `hash_equals()` en peticiones POST.
- Control de intentos fallidos de login por sesión.
- Headers HTTP de seguridad (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`).

## Hoja de Ruta (Roadmap)

- [ ] Confirmación de cuenta vía correo electrónico.
- [ ] Flujo de recuperación de contraseña con tokens de un solo uso.
- [ ] Dashboard administrativo para lectura de mensajes de contacto.
- [ ] Persistencia del bloqueo de intentos fallidos en base de datos.

## Licencia

Este proyecto está distribuido bajo la licencia **MIT**. Libre para modificar, distribuir y adaptar.
