<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/db.php';

// Configuración de Google Client
$client = new Google_Client(['client_id' => '811468665260-n80ebrjj5aq92199cqb7bvfmo5vkj5ps.apps.googleusercontent.com']); 
$id_token = $_POST['credential'] ?? null; 

if (!$id_token) {
    die("No se recibió el token de Google.");
}

$payload = $client->verifyIdToken($id_token);

if ($payload) {
    $email = $payload['email'];
    $nombre = $payload['name'];
    $google_id = $payload['sub']; 

    // Buscar usuario por EMAIL y obtener su ROL
    $sql = "SELECT u.id_usuario, u.nombre, u.estado, r.nombre AS rol_nombre 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user_data = $result->fetch_assoc()) {
        // --- ESCENARIO A: LOGIN DE USUARIO EXISTENTE ---
        
        if ($user_data['estado'] === 'bloqueado') {
            header("Location: ../login.php?error=bloqueado");
            exit();
        }

        $_SESSION['id_usuario'] = $user_data['id_usuario'];
        $_SESSION['nombre_usuario'] = $user_data['nombre'];
        $_SESSION['rol'] = $user_data['rol_nombre'];

        header("Location: ../dashboard.php");
        exit();

    } else {
        // --- ESCENARIO B: REGISTRO DE USUARIO NUEVO ---
        
        $rol_default = 2; // ID del rol para clientes/usuarios normales
        $estado_default = 'activo';
        $username_default = explode('@', $email)[0];
        $pass_dummy = ""; 

        // Insertar en la tabla usuarios
        $insert_sql = "INSERT INTO usuarios (nombre, username, email, id_rol, estado, google_id, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        
        $stmt_insert->bind_param("sssisss", $nombre, $username_default, $email, $rol_default, $estado_default, $google_id, $pass_dummy);
        
        try {
            if ($stmt_insert->execute()) {
                $_SESSION['id_usuario'] = $conn->insert_id;
                $_SESSION['nombre_usuario'] = $nombre;
                $_SESSION['rol'] = "Usuario"; 

                header("Location: ../dashboard.php");
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            die("Error crítico al registrar: " . $e->getMessage());
        }
    }
} else {
    echo "Token de Google no válido o expirado.";
}