<?php
session_start();
require_once '../config/db.php';

// Control de Acceso: Solo Administrador (Parte 6)
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../dashboard.php");
    exit();
}

// Lógica de Desbloqueo (Parte 4)
if (isset($_POST['desbloquear_id'])) {
    $admin_id = $_SESSION['id_usuario'];
    $user_to_unlock = $_POST['desbloquear_id'];
    $admin_password = $_POST['admin_pass'];

    // 1. Verificar contraseña del administrador para confirmar (Parte 4)
    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (password_verify($admin_password, $res['password'])) {
        // 2. Reiniciar intentos y desbloquear (Parte 4)
        $update = $conn->prepare("UPDATE usuarios SET estado = 'activo', intentos_fallidos = 0 WHERE id_usuario = ?");
        $update->bind_param("i", $user_to_unlock);
        $update->execute();
        
        $msg = "Usuario desbloqueado correctamente.";
    } else {
        $error = "Contraseña de administrador incorrecta. Acción cancelada.";
    }
}

// Obtener lista de usuarios (Parte 5)
$usuarios = $conn->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Admin</title>
    <link rel="stylesheet" href="../assets/css/usuarios.css">
</head>
<body>
    <div style="display: flex;">
        <div class="main-content">
    <div class="header-container">
        <h1>Gestión de Usuarios</h1>
        <a href="../dashboard.php" class="btn-back">🏠 Volver al Dashboard</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Intentos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['rol_nombre']; ?></td>
                    <td>
                        <span class="status-pill <?php echo $user['estado']; ?>">
                            <?php echo strtoupper($user['estado']); ?>
                        </span>
                    </td>
                    <td><?php echo $user['intentos_fallidos']; ?>/3</td>
                    <td>
                        <?php if($user['estado'] === 'bloqueado'): ?>
                        <form method="POST" class="unlock-form">
                            <input type="hidden" name="desbloquear_id" value="<?php echo $user['id_usuario']; ?>">
                            <input type="password" name="admin_pass" placeholder="Tu clave" required>
                            <button type="submit" class="unlock-btn">Desbloquear</button>
                        </form>
                        <?php else: ?>
                            <span style="color: var(--text-muted); opacity: 0.5;">- Sin acciones -</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>