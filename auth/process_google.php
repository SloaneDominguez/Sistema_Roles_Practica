<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/db.php';

// 1. Configura tu ID de Cliente (El mismo que en el HTML)
$client = new Google_Client(['client_id' => '811468665260-n80ebrjj5aq92199cqb7bvfmo5vkj5ps.apps.googleusercontent.com']); 
$id_token = $_POST['credential']; 

$payload = $client->verifyIdToken($id_token);

if ($payload) {
    $email = $payload['email'];
    $nombre = $payload['name'];
    $google_id = $payload['sub']; // ID único de Google del usuario

    // 2. Buscar si el usuario ya existe por su EMAIL
    $sql = "SELECT u.id_usuario, u.nombre, u.estado, r.nombre AS rol_nombre 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user_data = $result->fetch_assoc()) {
        // --- ESCENARIO A: EL USUARIO YA EXISTE (LOGIN) ---
        
        if ($user_data['estado'] === 'bloqueado') {
            header("Location: ../login.php?error=bloqueado");
            exit();
        }

        // Crear la sesión directamente (Google ya validó quién es)
        $_SESSION['id_usuario'] = $user_data['id_usuario'];
        $_SESSION['nombre_usuario'] = $user_data['nombre'];
        $_SESSION['rol'] = $user_data['rol_nombre'];

        header("Location: ../dashboard.php");
        exit();

    } else {
        // --- ESCENARIO B: EL USUARIO ES NUEVO (REGISTRO) ---
        
        // Insertamos al nuevo usuario. 
        // IMPORTANTE: id_rol = 2 (asegúrate que el 2 exista en tu tabla 'roles')
        $rol_default = 2; 
        $estado_default = 'activo';
        $username_default = explode('@', $email)[0]; // Usamos la primera parte del correo como username

        $insert_sql = "INSERT INTO usuarios (nombre, username, email, id_rol, estado, google_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sssiss", $nombre, $username_default, $email, $rol_default, $estado_default, $google_id);
        
        if ($stmt_insert->execute()) {
            $_SESSION['id_usuario'] = $conn->insert_id;
            $_SESSION['nombre_usuario'] = $nombre;
            $_SESSION['rol'] = "Usuario"; // O el nombre que corresponda al ID 2

            header("Location: ../dashboard.php");
            exit();
        } else {
            echo "Error al registrar usuario nuevo.";
        }
    }
} else {
    echo "Token de Google no válido o expirado.";
}