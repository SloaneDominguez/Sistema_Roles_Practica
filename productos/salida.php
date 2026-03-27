<?php
session_start();
require_once '../config/db.php';

// Validar que sea Vendedor o Administrador
if ($_SESSION['rol'] === 'Usuario') {
    die("No tienes permiso para registrar salidas.");
}

$id_producto = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = $_POST['cantidad'];
    $id_u = $_SESSION['id_usuario'];

    // 1. Restar del stock
    $sql_update = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ii", $cantidad, $id_producto);
    
    if ($stmt->execute()) {
        // 2. Registrar el movimiento (Parte 1: Logs/Movimientos)
        $sql_mov = "INSERT INTO movimientos (id_producto, tipo, cantidad, realizado_por) VALUES (?, 'salida', ?, ?)";
        $stmt_mov = $conn->prepare($sql_mov);
        $stmt_mov->bind_param("iii", $id_producto, $cantidad, $id_u);
        $stmt_mov->execute();

        header("Location: gestion.php");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div class="auth-container">
        <h2>Registrar Salida</h2>
        <form method="POST">
            <div class="form-group">
                <label>Cantidad a retirar</label>
                <input type="number" name="cantidad" min="1" required>
            </div>
            <button type="submit" class="btn">Confirmar Salida</button>
            <br><br>
            <a href="gestion.php" style="color: var(--accent-cyan);">Cancelar</a>
        </form>
    </div>
</body>
</html>