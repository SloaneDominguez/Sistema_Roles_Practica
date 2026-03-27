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

// 2. Lógica para Eliminar
if (isset($_GET['eliminar'])) {
    if ($rol_sesion === 'Administrador' || $rol_sesion === 'Usuario') {
        $id_p = intval($_GET['eliminar']);

        // Obtener nombre para el log
        $stmt_info = $conn->prepare("SELECT nombre FROM productos WHERE id_producto = ?");
        $stmt_info->bind_param("i", $id_p);
        $stmt_info->execute();
        $res_info = $stmt_info->get_result();
        $prod_info = $res_info->fetch_assoc();
        $nombre_producto = $prod_info ? $prod_info['nombre'] : "ID: $id_p";

        try {
            $stmt_del = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
            $stmt_del->bind_param("i", $id_p);
            
            if ($stmt_del->execute()) {
                registrarActividad($conn, $id_sesion, "Eliminó el producto: " . $nombre_producto);
                header("Location: gestion.php?status=success&msj=Producto eliminado correctamente");
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            // Error 1451: Restricción de llave foránea (tiene ventas)
            if ($e->getCode() == 1451) {
                header("Location: gestion.php?status=error_fk");
                exit();
            } else {
                header("Location: gestion.php?status=error_gen");
                exit();
            }
        }
    } else {
        header("Location: gestion.php?status=error_permiso");
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'error_fk') {
            Swal.fire({
                icon: 'error',
                title: 'No se puede eliminar',
                text: 'Este producto ya tiene ventas registradas en el sistema y no puede ser borrado para mantener la integridad contable.',
                confirmButtonColor: '#3085d6',
                background: '#1e293b',
                color: '#fff'
            });
        } else if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Logrado!',
                text: 'El producto ha sido removido del inventario.',
                timer: 2000,
                showConfirmButton: false,
                background: '#1e293b',
                color: '#fff'
            });
        } else if (status === 'error_permiso') {
            Swal.fire({
                icon: 'warning',
                title: 'Acceso Denegado',
                text: 'No tienes los permisos necesarios para eliminar productos.',
                background: '#1e293b',
                color: '#fff'
            });
        }
    </script>
</body>
</html>