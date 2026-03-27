<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'];

    // =========================
    // REGISTRO
    // =========================
    if ($action === 'register') {

        $nombre   = $_POST['nombre'];
        $email    = $_POST['email'];
        $user     = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $rol_default = 2;

        $sql = "INSERT INTO usuarios (nombre, email, username, password, id_rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $email, $user, $password, $rol_default);

        if ($stmt->execute()) {
            header("Location: login.php?success=1");
        } else {
            header("Location: login.php?error=registro");
        }
        exit();
    }

    // =========================
    // LOGIN
    // =========================
    if ($action === 'login') {

        $user = $_POST['username'];
        $pass = $_POST['password'];

        $sql = "SELECT u.id_usuario, u.nombre, u.password, u.estado, u.intentos_fallidos, r.nombre AS rol_nombre
                FROM usuarios u
                JOIN roles r ON u.id_rol = r.id_rol
                WHERE u.username = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user_data = $result->fetch_assoc()) {

            // 🔒 Verificar si está bloqueado
            if ($user_data['estado'] === 'bloqueado') {
                header("Location: login.php?error=bloqueado");
                exit();
            }

            // 🔑 Verificar contraseña
            if (password_verify($pass, $user_data['password'])) {

                // Reset intentos
                $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = 0 WHERE id_usuario = ?");
                $stmt->bind_param("i", $user_data['id_usuario']);
                $stmt->execute();

                // Crear sesión
                $_SESSION['id_usuario'] = $user_data['id_usuario'];
                $_SESSION['nombre_usuario'] = $user_data['nombre'];
                $_SESSION['rol'] = $user_data['rol_nombre'];

                // Log
                $stmt = $conn->prepare("INSERT INTO logs (id_usuario, accion) VALUES (?, ?)");
                $accion = "Inicio de sesión exitoso";
                $stmt->bind_param("is", $user_data['id_usuario'], $accion);
                $stmt->execute();

                header("Location: ../dashboard.php");
                exit();

            } else {

                // ❌ Intento fallido
                $nuevos_intentos = $user_data['intentos_fallidos'] + 1;

                if ($nuevos_intentos >= 3) {

                    $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = ?, estado = 'bloqueado' WHERE id_usuario = ?");
                    $stmt->bind_param("ii", $nuevos_intentos, $user_data['id_usuario']);
                    $stmt->execute();

                    header("Location: login.php?error=bloqueado");

                } else {

                    $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = ? WHERE id_usuario = ?");
                    $stmt->bind_param("ii", $nuevos_intentos, $user_data['id_usuario']);
                    $stmt->execute();

                    header("Location: login.php?error=auth&intento=$nuevos_intentos");
                }
                exit();
            }

        } else {
            header("Location: login.php?error=usuario");
            exit();
        }
    }
}
?>