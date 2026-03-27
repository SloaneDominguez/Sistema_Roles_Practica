<?php
session_start();
require_once 'config/db.php';

// 1. Validar que exista sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.php");
    exit();
}

$id_usuario_sesion = $_SESSION['id_usuario'];
$nombre_usuario = $_SESSION['nombre_usuario'];
$rol_usuario = $_SESSION['rol']; // Administrador, Usuario o Vendedor

// --- CONSULTAS PARA DATOS REALES ---

// A. Obtener total de productos en stock
$sql_stock = "SELECT SUM(stock) as total FROM productos";
$res_stock = $conn->query($sql_stock);
$total_stock = ($res_stock && $res_stock->num_rows > 0) ? $res_stock->fetch_assoc()['total'] : 0;
$total_stock = $total_stock ?? 0;

// B. Obtener ventas del día
$ventas_dia = 0;
$sql_check_ventas = "SHOW TABLES LIKE 'ventas'";
$exists_ventas = $conn->query($sql_check_ventas);

if ($exists_ventas->num_rows > 0) {
    $sql_ventas = "SELECT SUM(total) as diario FROM ventas WHERE DATE(fecha_venta) = CURDATE()";
    $res_ventas = $conn->query($sql_ventas);
    if ($res_ventas) {
        $ventas_dia = $res_ventas->fetch_assoc()['diario'] ?? 0;
    }
}

// C. Obtener usuarios activos (Solo si es Administrador)
$usuarios_activos = 0;
if ($rol_usuario === 'Administrador') {
    $sql_users = "SELECT COUNT(*) as activos FROM usuarios WHERE estado = 'activo'";
    $res_users = $conn->query($sql_users);
    if ($res_users) {
        $usuarios_activos = $res_users->fetch_assoc()['activos'];
    }
}

// --- IMPLEMENTACIÓN: CONSULTA DE ACTIVIDAD REAL FILTRADA POR ROL ---
if ($rol_usuario === 'Administrador') {
    // El Administrador supervisa todas las acciones de todos los usuarios
    $sql_logs = "SELECT a.accion, a.fecha, u.username 
                 FROM actividad_logs a 
                 INNER JOIN usuarios u ON a.id_usuario = u.id_usuario 
                 ORDER BY a.fecha DESC LIMIT 5";
} else {
    // Los Usuarios y Vendedores solo ven su propio historial de acciones
    $sql_logs = "SELECT a.accion, a.fecha, u.username 
                 FROM actividad_logs a 
                 INNER JOIN usuarios u ON a.id_usuario = u.id_usuario 
                 WHERE a.id_usuario = '$id_usuario_sesion'
                 ORDER BY a.fecha DESC LIMIT 5";
}

$res_logs = $conn->query($sql_logs);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TechSolutions</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    
</head>
<body class="app--dark">

    <nav class="sidebar">
        <div class="sidebar-header" style="padding: 2rem; font-weight: bold; font-size: 1.5rem; color: var(--accent-cyan);">
            TechSolutions
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item active">🏠 Dashboard</a>

            <?php if ($rol_usuario === 'Administrador'): ?>
                <a href="admin/usuarios.php" class="menu-item">👥 Gestión de Usuarios</a>
            <?php endif; ?>

            <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Usuario'): ?>
                <a href="productos/gestion.php" class="menu-item">📦 Gestión de Productos</a>
            <?php endif; ?>

            <?php if ($rol_usuario === 'Vendedor' || $rol_usuario === 'Administrador'): ?>
                <a href="ventas/panel.php" class="menu-item">💰 Panel de Ventas</a>
            <?php endif; ?>

            <a href="perfil.php" class="menu-item">👤 Mi Perfil</a>
        </div>

        <div style="position: absolute; bottom: 0; width: 100%; padding: 1rem;">
            <a href="auth/logout.php" class="logout-btn" style="background: #ef4444; color: white; display: block; text-align: center; padding: 0.8rem; border-radius: 0.5rem; text-decoration: none; font-weight: bold;">Cerrar Sesión</a>
        </div>
    </nav>

    <main class="main-content">
        <header>
            <h1 style="margin: 0;">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h1>
            <p style="margin-top: 5px;">Rol: <span class="role-badge"><?php echo htmlspecialchars($rol_usuario); ?></span></p>
        </header>

        <section class="header-stats">
            <div class="stat-card">
                <h3>Productos en Stock</h3>
                <p><?php echo number_format($total_stock); ?> <small style="font-size: 0.8rem; color: var(--text-muted);">Uds</small></p>
            </div>

            <div class="stat-card" style="border-left-color: var(--accent-cyan);">
                <h3>Ventas del Día</h3>
                <p>$<?php echo number_format($ventas_dia, 2); ?></p>
            </div>

            <?php if ($rol_usuario === 'Administrador'): ?>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <h3>Usuarios Activos</h3>
                <p><?php echo $usuarios_activos; ?></p>
            </div>
            <?php endif; ?>
        </section>

        <section class="info-section">
            <div class="stat-card" style="width: 100%; border-left: none; border-top: 4px solid var(--accent-purple);">
                <h2>Resumen de Actividad</h2>
                <p style="font-size: 1.1rem; color: var(--text-muted);">
                    <?php echo ($rol_usuario === 'Administrador') ? 'Supervisión de acciones globales del sistema:' : 'Tu historial reciente de acciones en la plataforma:'; ?>
                </p>
                
                <div class="activity-list" style="margin-top: 1.5rem;">
                    <?php if ($res_logs && $res_logs->num_rows > 0): ?>
                        <?php while($log = $res_logs->fetch_assoc()): ?>
                            <div class="activity-item">
                                <span>
                                    <span class="user-tag"><?php echo htmlspecialchars($log['username']); ?>:</span> 
                                    <?php echo htmlspecialchars($log['accion']); ?>
                                </span>
                                <span class="time-tag">
                                    <?php echo date('d/m H:i', strtotime($log['fecha'])); ?>
                                </span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">
                        <p style="font-size: 0.9rem; font-style: italic; color: var(--text-muted);">
                            No hay registros de actividad disponibles para mostrar.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

</body>
</html>