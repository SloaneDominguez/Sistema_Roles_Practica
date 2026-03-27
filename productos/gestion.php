<?php
session_start();
require_once '../config/db.php';

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_sesion = $_SESSION['id_usuario'];
$rol_sesion = $_SESSION['rol'];

// 2. Lógica para Eliminar (Unificada y con Registro de Actividad)
if (isset($_GET['eliminar'])) {
    // Verificamos permisos: Solo Admin o Usuario pueden eliminar
    if ($rol_sesion === 'Administrador' || $rol_sesion === 'Usuario') {
        $id_p = intval($_GET['eliminar']);

        // Opcional: Obtener el nombre del producto antes de borrarlo para un log más claro
        $stmt_info = $conn->prepare("SELECT nombre FROM productos WHERE id_producto = ?");
        $stmt_info->bind_param("i", $id_p);
        $stmt_info->execute();
        $res_info = $stmt_info->get_result();
        $prod_info = $res_info->fetch_assoc();
        $nombre_producto = $prod_info ? $prod_info['nombre'] : "ID: $id_p";

        // Ejecutar eliminación
        $stmt_del = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
        $stmt_del->bind_param("i", $id_p);

        if ($stmt_del->execute()) {
            // --- ESTO ES LO QUE ALIMENTA EL RESUMEN DE ACTIVIDAD ---
            $accion_log = "Eliminó el producto: " . $nombre_producto;
            registrarActividad($conn, $id_sesion, $accion_log);
            // -------------------------------------------------------

            header("Location: gestion.php?msj=Producto eliminado correctamente");
            exit();
        }
    } else {
        header("Location: gestion.php?error=No tienes permisos para realizar esta acción");
        exit();
    }
}

// 3. Obtener la lista de productos (incluyendo quién lo creó si existe la columna)
$sql = "SELECT p.*, u.username as creador 
        FROM productos p 
        LEFT JOIN usuarios u ON p.id_usuario_creador = u.id_usuario";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario - TechSolutions</title>
    <link rel="stylesheet" href="../assets/css/gestion.css">
    
</head>
<body class="app--dark">
    <div class="container">
        <header style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0;">Gestión de Productos</h1>
                <p style="color: var(--text-muted);">Administra el inventario y controla el stock disponible.</p>
            </div>
            <a href="../dashboard.php" class="btn-back">
            <span>🏠</span> Volver al Dashboard
            </a>
        </header>

        <hr style="border: 0; border-top: 1px solid #334155; margin: 2rem 0;">

        <?php if(isset($_GET['msj'])): ?>
            <div class="alert"><?php echo htmlspecialchars($_GET['msj']); ?></div>
        <?php endif; ?>

        <?php if ($rol_sesion === 'Administrador'): ?>
            <a href="nuevo.php" class="btn-new">+ Agregar Nuevo Producto</a>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Creador</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while($prod = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div style="font-weight: bold; color: white;"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                                <small style="color: var(--text-muted);">ID: #<?php echo $prod['id_producto']; ?></small>
                            </td>
                            <td style="color: #10b981; font-weight: bold;">$<?php echo number_format($prod['precio'], 2); ?></td>
                            <td>
                                <span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 4px;">
                                    <?php echo $prod['stock']; ?> uds
                                </span>
                            </td>
                            <td style="color: var(--text-muted);">
                                <?php echo !empty($prod['creador']) ? htmlspecialchars($prod['creador']) : 'Sistema'; ?>
                            </td>
                            <td>
                                <?php if ($rol_sesion === 'Administrador' || $rol_sesion === 'Usuario'): ?>
                                    <a href="editar.php?id=<?php echo $prod['id_producto']; ?>" class="btn-action btn-edit">Editar</a>
                                    <a href="gestion.php?eliminar=<?php echo $prod['id_producto']; ?>" 
                                       class="btn-action btn-delete" 
                                       onclick="return confirm('¿Estás seguro de eliminar este producto permanentemente?')">Borrar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                                No hay productos registrados en el inventario actualmente.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>