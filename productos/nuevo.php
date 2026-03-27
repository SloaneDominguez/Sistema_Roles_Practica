<?php
session_start();
require_once '../config/db.php';

// 1. Validar que el usuario sea Administrador para poder AGREGAR
// (Puedes ajustar esto si quieres que los 'Usuario' también agreguen)
$es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$es_admin) {
        $error = "No tienes permisos para agregar productos.";
    } else {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $stock  = $_POST['stock'];
        
        // 2. Capturar el ID del usuario que está logueado
        $id_usuario_creador = $_SESSION['id_usuario'];

        // 3. Preparar la consulta incluyendo la columna del creador
        $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock, id_usuario_creador) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdii", $nombre, $precio, $stock, $id_usuario_creador);

        if ($stmt->execute()) {
            // --- ESTA ES LA PARTE QUE ALIMENTA EL RESUMEN DE ACTIVIDAD ---
            registrarActividad($conn, $_SESSION['id_usuario'], "Creó un nuevo producto: " . $_POST['nombre']);
            // -------------------------------------------------------------

            // Redirigir con mensaje de éxito
            header("Location: gestion.php?msj=Producto agregado correctamente");
            exit();
        } else {
            $error = "Error al guardar el producto: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto - TechSolutions</title>
    <link rel="stylesheet" href="../assets/css/nuevo.css">
</head>
<body>

    <div class="form-container">
        <h2>Nuevo Producto</h2>

        <?php if ($error !== ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" placeholder="Ej: Laptop Gamer" required>
            </div>

            <div class="form-group">
                <label>Precio ($)</label>
                <input type="number" name="precio" step="0.01" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label>Stock Inicial</label>
                <input type="number" name="stock" placeholder="0" required>
            </div>

            <button type="submit" class="btn-save">Guardar Producto</button>
        </form>
        
        <a href="gestion.php" class="back-link"> Volver</a>
    </div>

</body>
</html>