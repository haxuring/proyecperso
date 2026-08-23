# LoginRegister — Sistema de login y registro con PHP y MySQL

Proyecto personal básico de autenticación de usuarios: registro, inicio de sesión y panel privado,
construido con PHP nativo (PDO), MySQL, HTML5 semántico y CSS3 sin frameworks.

## Características

- Registro de usuarios con validación en el servidor.
- Contraseñas cifradas con `password_hash()` / `password_verify()`.
- Consultas con sentencias preparadas (protección frente a inyección SQL).
- Sesiones PHP con regeneración de ID al iniciar sesión.
- Panel privado (`dashboard.php`) accesible solo para usuarios autenticados.
- HTML5 semántico: un único `<h1>` por página, jerarquía correcta de encabezados
  y etiquetas como `<header>`, `<nav>`, `<main>`, `<section>`, `<aside>` y `<footer>`.
- Metadatos SEO por página: título y `meta description` únicos en cada vista.

## Tecnologías

| Tecnología   | Uso                                        |
| ------------ | ------------------------------------------ |
| PHP 8+       | Lógica del servidor y sesiones             |
| MySQL        | Almacenamiento de usuarios                 |
| PDO          | Acceso a la base de datos (preparado)      |
| HTML5 / CSS3 | Interfaz semántica, accesible y responsive |

## Estructura del proyecto

```
login-register-php/
├── config/
│   └── database.example.php   # Plantilla de conexión (el real va en .gitignore)
├── includes/
│   ├── header.php             # <head> SEO + navegación común
│   └── footer.php             # Cierre común del documento
├── css/
│   └── styles.css
├── sql/
│   └── database.sql           # Esquema de la base de datos
├── index.php                  # Portada pública
├── register.php               # Registro de usuarios
├── login.php                  # Inicio de sesión
├── dashboard.php              # Panel privado (requiere sesión)
├── logout.php                 # Cierre de sesión
└── README.md
```

## Puesta en marcha

### 1. Clona el repositorio

```bash
git clone https://github.com/tu-usuario/login-register-php.git
cd login-register-php
```

### 2. Importa la base de datos

Importa `sql/database.sql` desde phpMyAdmin o por consola:

```bash
mysql -u root -p < sql/database.sql
```

### 3. Configura la conexión

Copia la plantilla y ajusta tus credenciales:

```bash
cp config/database.example.php config/database.php
```

El archivo real está ignorado por `.gitignore`, así que tus credenciales
nunca se subirán al repositorio.

### 4. Arranca el servidor

Con el servidor integrado de PHP:

```bash
php -S localhost:8000
```

Abre `http://localhost:8000` en tu navegador.

## Seguridad implementada

- `password_hash()` con algoritmo bcrypt por defecto de PHP.
- Sentencias preparadas con parámetros nombrados en todas las consultas.
- `htmlspecialchars()` en toda salida dinámica (prevención de XSS).
- `session_regenerate_id(true)` tras login/registro (prevención de fijación de sesión).
- Mensajes de error genéricos en el login (no se revela si el email existe).

## Mejoras posibles

- Protección CSRF con tokens en los formularios.
- Verificación del correo electrónico al registrarse.
- Límite de intentos de inicio de sesión (rate limiting).
- Recuperación de contraseña por email.

## Licencia

MIT — siéntete libre de usarlo, estudiarlo y modificarlo.
