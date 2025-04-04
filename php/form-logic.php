<?php
// Configuración para recibir correos
$recipient_email = "no-reply@duckdev.site"; 
$email_subject = "Mensaje de Contacto DUCK DEV";

// Verificar que sea una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario y sanitizarlos
    $name = filter_var($_POST["name"] ?? "", FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"] ?? "", FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST["comment"] ?? "", FILTER_SANITIZE_STRING);
    
    // Validar datos
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "El nombre es obligatorio";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Se requiere un correo electrónico válido";
    }
    
    if (empty($message)) {
        $errors[] = "El mensaje es obligatorio";
    }
    
    // Si no hay errores, procesar el formulario
    if (empty($errors)) {
        // Preparar el correo
        $email_content = "Nombre: $name\n";
        $email_content .= "Correo: $email\n\n";
        $email_content .= "Mensaje:\n$message\n";
        
        // Cabeceras del correo
        $headers = "From: $name <$email>";
        
        // Enviar el correo
        if (mail($recipient_email, $email_subject, $email_content, $headers)) {
            $response = [
                "status" => "success",
                "message" => "¡Gracias! Tu mensaje ha sido enviado."
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Lo sentimos, hubo un problema al enviar tu mensaje."
            ];
        }
    } else {
        $response = [
            "status" => "error",
            "message" => implode(", ", $errors)
        ];
    }
    
    // Devolver respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Si no es una solicitud POST, redirigir a la página principal
header("Location: index.html");
?>