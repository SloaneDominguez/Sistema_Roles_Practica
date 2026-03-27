<?php
session_start();
require_once '../config/db.php';

// Validar sesión y rol
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'Vendedor' && $_SESSION['rol'] !== 'Administrador')) {
    header("Location: ../dashboard.php?error=no_autorizado");
    exit();
}

// Consultar productos disponibles
$productos = $conn->query("SELECT id_producto, nombre, precio, stock FROM productos WHERE stock > 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Ventas</title>
    <link rel="stylesheet" href="../assets/css/panel.css">
</head>
<body class="app--dark">
    <div class="main-container">
    <header class="main-header">
        <div class="header-info">
            <h2>Panel de Ventas (Vendedor)</h2>
            <p class="subtitle">Control de inventario y salidas</p>
        </div>
        <a href="../dashboard.php" class="btn-back">🏠 Volver al Dashboard</a>
        </header>

    </div>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert-box alert-success">✓ Venta registrada y stock actualizado.</div>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock Actual</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    
                    <!-- CORREGIDO -->
                    <td>$<?php echo number_format($row['precio'], 2); ?></td>
                    
                    <td><?php echo $row['stock']; ?> unidades</td>
                    <td>
                        <form action="procesar_venta.php" method="POST" style="display:flex; gap:5px;">
                            <input type="hidden" name="id_producto" value="<?php echo $row['id_producto']; ?>">
                            
                            <input type="number" 
                                   name="cantidad" 
                                   min="1" 
                                   max="<?php echo $row['stock']; ?>" 
                                   value="1" 
                                   required 
                                   style="width:60px;">
                            
                            <button type="submit" class="btn" style="padding:5px 10px;">Vender</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        
    </div>
    
</body>
</html>