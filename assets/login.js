// Espera a que el DOM esté listo antes de buscar elementos
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const alertBox = document.getElementById('client-errors');

    if (!form) return; // si no hay formulario, no hace nada

    // Valida en cliente antes de enviar al servidor
    form.addEventListener('submit', (event) => {
        const errors = [];
        const username = form.username.value.trim();
        const password = form.password.value;

        // Reglas para el usuario
        if (!username) {
            errors.push('El usuario es obligatorio.');
        } else if (!/^[A-Za-z0-9_]{4,30}$/.test(username)) {
            errors.push('El usuario debe ser alfanumérico (4-30) y sin espacios.');
        }

        // Reglas para la contraseña
        if (!password) {
            errors.push('La contraseña es obligatoria.');
        } else if (password.length < 8) {
            errors.push('La contraseña debe tener al menos 8 caracteres.');
        }

        // Si hay errores, muestra alerta y detiene el submit
        if (errors.length > 0) {
            event.preventDefault();
            alertBox.hidden = false;
            alertBox.innerHTML = `<strong>Validación cliente:</strong><ul>${errors.map(e => `<li>${e}</li>`).join('')}</ul>`;
            alertBox.focus({preventScroll: true});
        } else {
            alertBox.hidden = true; // limpia alertas y permite envío
        }
    });
});
