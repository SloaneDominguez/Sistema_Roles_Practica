<?php
session_start();
require_once 'config/db.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.php");
    exit();
}

$id_user = $_SESSION['id_usuario'];

// Consultar datos actuales del usuario
$stmt = $conn->prepare("SELECT nombre, email, username, id_rol FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    die("Error: Usuario no encontrado.");
}

// Inicializar variable
$success = "";

// Procesar actualización si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validar datos
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_email = trim($_POST['email']);

    if (!empty($nuevo_nombre) && !empty($nuevo_email)) {

        // Validar formato de email
        if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
            $error = "Correo electrónico inválido.";
        } else {

            $update = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?");
            $update->bind_param("ssi", $nuevo_nombre, $nuevo_email, $id_user);
            
            if ($update->execute()) {
                $_SESSION['nombre_usuario'] = $nuevo_nombre;

                $success = "Perfil actualizado correctamente.";

                // Refrescar datos
                $user_data['nombre'] = $nuevo_nombre;
                $user_data['email'] = $nuevo_email;
            } else {
                $error = "Error al actualizar el perfil.";
            }
        }

    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="assets/css/perfil.css">
</head>

<body class="app--dark">

<div class="auth-container" style="max-width: 500px; margin-top: 50px;">
    
    <h2>Configuración de Perfil</h2>

    <?php if(!empty($success)): ?>
        <div class="alert-box alert-success">
            <span class="alert-icon">✓</span>
            <span><?php echo $success; ?></span>
        </div>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
        <div class="alert-box alert-error">
            <span class="alert-icon">⚠</span>
            <span><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($user_data['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Nombre de Usuario</label>
            <input type="text" value="<?php echo htmlspecialchars($user_data['username']); ?>" disabled style="background: #334155;">
            <small style="color: #94a3b8;">El nombre de usuario no se puede cambiar.</small>
        </div>
        
        <div class="actions-container">
            <button type="submit" class="btn-save">Guardar Cambios</button>
            
            <a href="dashboard.php" class="btn-back-link">
                Volver al Dashboard
            </a>
        </div>
    </form>
</div>

</body>
</html>