(() => {
    'use strict';

    const $ = (selector, contexto = document) => contexto.querySelector(selector);
    const $$ = (selector, contexto = document) => [...contexto.querySelectorAll(selector)];

    const menuBoton = $('.nav-alternar');
    const menuLista = $('#menu-principal');

    if (menuBoton && menuLista) {
        const alternarMenu = (abierto) => {
            menuLista.classList.toggle('abierto', abierto);
            menuBoton.setAttribute('aria-expanded', String(abierto));
        };

        menuBoton.addEventListener('click', () => {
            alternarMenu(!menuLista.classList.contains('abierto'));
        });

        menuLista.addEventListener('click', (evento) => {
            if (evento.target.closest('a')) {
                alternarMenu(false);
            }
        });

        document.addEventListener('keydown', (evento) => {
            if (evento.key === 'Escape') {
                alternarMenu(false);
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                alternarMenu(false);
            }
        });
    }

    const botonTema = $('.alternar-tema');

    if (botonTema) {
        botonTema.addEventListener('click', () => {
            const actual = document.documentElement.getAttribute('data-tema');
            const nuevo = actual === 'oscuro' ? 'claro' : 'oscuro';
            document.documentElement.setAttribute('data-tema', nuevo);
            localStorage.setItem('tema', nuevo);
        });
    }

    $$('[data-alternar-password]').forEach((boton) => {
        const entrada = document.getElementById(boton.dataset.alternarPassword);

        if (!entrada) {
            return;
        }

        boton.addEventListener('click', () => {
            const visible = entrada.type === 'text';
            entrada.type = visible ? 'password' : 'text';
            boton.setAttribute('aria-pressed', String(!visible));
            boton.setAttribute(
                'aria-label',
                visible ? 'Mostrar contraseña' : 'Ocultar contraseña'
            );
            $$('.icono-ojo, .icono-ojo-tachado', boton).forEach((icono) => {
                icono.classList.toggle('oculto');
            });
        });
    });

    const campoPassword = $('#password');
    const medidorFuerza = $('#medidor-fuerza');
    const textoFuerza = $('#texto-fuerza');

    const calcularFuerza = (clave) => {
        let puntos = 0;

        if (clave.length >= 8) puntos += 1;
        if (clave.length >= 12) puntos += 1;
        if (/[a-z]/.test(clave) && /[A-Z]/.test(clave)) puntos += 1;
        if (/\d/.test(clave)) puntos += 1;
        if (/[^A-Za-z0-9]/.test(clave)) puntos += 1;

        return Math.min(puntos, 4);
    };

    if (campoPassword && medidorFuerza && textoFuerza) {
        const niveles = ['Muy débil', 'Débil', 'Aceptable', 'Buena', 'Excelente'];

        campoPassword.addEventListener('input', () => {
            const nivel = calcularFuerza(campoPassword.value);

            medidorFuerza.value = nivel;
            textoFuerza.dataset.nivel = String(nivel);
            textoFuerza.textContent = `Seguridad de la contraseña: ${niveles[nivel]}`;
        });
    }

    const formularioRegistro = $('#form-registro');

    if (formularioRegistro) {
        const password = $('#password');
        const confirmacion = $('#password_confirm');
        const avisoCoincidencia = $('#error-coincidencia');

        const comprobarCoincidencia = () => {
            if (!confirmacion.value || password.value === confirmacion.value) {
                confirmacion.setAttribute('aria-invalid', 'false');
                avisoCoincidencia.textContent = '';
                return true;
            }

            confirmacion.setAttribute('aria-invalid', 'true');
            avisoCoincidencia.textContent = 'Las contraseñas no coinciden.';
            return false;
        };

        confirmacion.addEventListener('input', comprobarCoincidencia);

        formularioRegistro.addEventListener('submit', (evento) => {
            if (!comprobarCoincidencia()) {
                evento.preventDefault();
                confirmacion.focus();
            }
        });
    }

    const areaMensaje = $('#mensaje');
    const contadorMensaje = $('#contador-mensaje');

    if (areaMensaje && contadorMensaje) {
        const maximo = Number(areaMensaje.getAttribute('maxlength')) || 1000;

        const actualizarContador = () => {
            contadorMensaje.textContent = `${areaMensaje.value.length} / ${maximo}`;
        };

        areaMensaje.addEventListener('input', actualizarContador);
        actualizarContador();
    }

    const cerrarAviso = (aviso) => {
        aviso.classList.add('aviso--cerrando');
        setTimeout(() => aviso.remove(), 350);
    };

    $$('[data-avisos] .aviso').forEach((aviso) => {
        const botonCerrar = document.createElement('button');

        botonCerrar.type = 'button';
        botonCerrar.className = 'aviso-cerrar';
        botonCerrar.innerHTML = '&times;';
        botonCerrar.setAttribute('aria-label', 'Cerrar aviso');
        botonCerrar.addEventListener('click', () => cerrarAviso(aviso));

        aviso.append(botonCerrar);
        setTimeout(() => cerrarAviso(aviso), 6000);
    });

    const elementosRevelar = $$('.revelar');
    const reduceMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!('IntersectionObserver' in window) || reduceMovimiento) {
        elementosRevelar.forEach((elemento) => elemento.classList.add('visible'));
    } else {
        const observador = new IntersectionObserver(
            (entradas) => {
                entradas.forEach((entrada) => {
                    if (entrada.isIntersecting) {
                        entrada.target.classList.add('visible');
                        observador.unobserve(entrada.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        elementosRevelar.forEach((elemento) => observador.observe(elemento));
    }
})();
