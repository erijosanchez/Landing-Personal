document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formcontact');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Crear objeto FormData para recopilar datos del formulario
        const formData = new FormData(form);
        
        // Deshabilitar botón mientras se envía
        const submitButton = document.getElementById('submitbutton');
        submitButton.disabled = true;
        submitButton.textContent = 'Enviando...';
        
        // Enviar datos usando fetch
        fetch('process_form.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Crear un elemento para mostrar el mensaje de respuesta
            const messageElement = document.createElement('div');
            messageElement.className = data.status === 'success' ? 'alert alert-success' : 'alert alert-danger';
            messageElement.textContent = data.message;
            
            // Insertar el mensaje después del formulario
            form.parentNode.insertBefore(messageElement, form.nextSibling);
            
            // Si fue exitoso, limpiar el formulario
            if (data.status === 'success') {
                form.reset();
            }
            
            // Restaurar el botón
            submitButton.disabled = false;
            submitButton.textContent = 'Enviar';
            
            // Eliminar el mensaje después de 5 segundos
            setTimeout(() => {
                messageElement.remove();
            }, 5000);
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Mostrar mensaje de error
            const messageElement = document.createElement('div');
            messageElement.className = 'alert alert-danger';
            messageElement.textContent = 'Ocurrió un error al procesar tu solicitud. Por favor, intenta de nuevo.';
            form.parentNode.insertBefore(messageElement, form.nextSibling);
            
            // Restaurar el botón
            submitButton.disabled = false;
            submitButton.textContent = 'Enviar';
        });
    });
});