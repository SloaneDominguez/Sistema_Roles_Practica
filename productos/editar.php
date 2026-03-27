<?php
session_start();
require_once '../config/db.php';

// Validar sesión y rol (Solo Admin o Usuario con permisos pueden editar)
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'Administrador' && $_SESSION['rol'] !== 'Usuario')) {
    header("Location: ../dashboard.php");
    exit();
}

// Verificar si se ha proporcionado un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion.php?error=id_no_valido");
    exit();
}

$id_producto = intval($_GET['id']);
$mensaje = "";
$clase_alerta = "";

// --- LÓGICA DE ACTUALIZACIÓN (Al enviar el formulario) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock  = $_POST['stock'];

    

    // Usar consultas preparadas para mayor seguridad
    $stmt_update = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id_producto = ?");
    $stmt_update->bind_param("sdii", $nombre, $precio, $stock, $id_producto);
    
    if ($stmt_update->execute()) {
        $mensaje = "✓ Producto actualizado correctamente.";
        registrarActividad($conn, $_SESSION['id_usuario'], "Actualizó el producto: " . $nombre);
        $clase_alerta = "alert-success";
    } else {
        $mensaje = "✕ Error al actualizar: " . $conn->error;
        $clase_alerta = "alert-error";
    }
    $stmt_update->close();
}

// --- LÓGICA DE LECTURA (Para llenar el formulario) ---
$sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    header("Location: gestion.php?error=producto_no_encontrado");
    exit();
}

$producto = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - TechSolutions</title>
    <link rel="stylesheet" href="../assets/css/editar.css"> 
</head>
<body class="app--dark">
    <div class="edit-container">
        <h2>Editar Producto</h2>

        <?php if ($mensaje !== ""): ?>
            <div class="alert-box <?php echo $clase_alerta; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>ID del Producto (No editable)</label>
                <input type="text" value="<?php echo $producto['id_producto']; ?>" disabled style="opacity: 0.5;">
            </div>

            <div class="form-group">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>

            <div class="form-group" style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label>Precio ($)</label>
                    <input type="number" name="precio" step="0.01" value="<?php echo $producto['precio']; ?>" required>
                </div>
                <div style="flex: 1;">
                    <label>Stock Actual</label>
                    <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required>
                </div>
            </div>

        <div class="actions-container" style="display: flex; flex-direction: column; width: 100%;">
    <button type="submit" class="btn-save">Guardar Cambios</button>
    <a href="gestion.php" class="btn-back-link">Volver</a>
</div>
    
</body>
</html>