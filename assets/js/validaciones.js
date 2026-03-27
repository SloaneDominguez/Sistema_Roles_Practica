document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        form.addEventListener("submit", function(event) {
            let isValid = true;
            const inputs = form.querySelectorAll("input[required]");

            inputs.forEach(input => {
                // 1. Validar campos obligatorios [cite: 94]
                if (!input.value.trim()) {
                    alert(`El campo ${input.previousElementSibling.innerText} es obligatorio.`);
                    isValid = false;
                }

                // 2. Validar formato de correo [cite: 95]
                if (input.type === "email" && !validateEmail(input.value)) {
                    alert("Por favor, ingrese un correo electrónico válido.");
                    isValid = false;
                }

                // 3. Longitud mínima de contraseña [cite: 96]
                if (input.type === "password" && input.value.length < 6) {
                    alert("La contraseña debe tener al menos 6 caracteres.");
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault(); // Detiene el envío si hay errores
            }
        });
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});