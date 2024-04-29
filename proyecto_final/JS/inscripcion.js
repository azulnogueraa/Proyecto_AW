document.addEventListener('DOMContentLoaded', function() {
    const siguienteDatosBtn = document.getElementById('siguienteDatos');
    const confirmarInscripcionBtn = document.getElementById('confirmarInscripcion');
    const pasoDatos = document.getElementById('pasoDatos');
    const pasoPago = document.getElementById('pasoPago');

    siguienteDatosBtn.addEventListener('click', function() {
        // Aquí puedes agregar validaciones antes de pasar al siguiente paso
        pasoDatos.classList.remove('active');
        pasoPago.classList.add('active');
    });

    confirmarInscripcionBtn.addEventListener('click', function() {
        // Aquí puedes agregar la lógica para confirmar la inscripción
        alert('¡Inscripción confirmada!');
        // Redirigir a una página de confirmación o realizar otras acciones
    });
});

