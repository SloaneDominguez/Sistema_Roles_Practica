<?php
session_start();
require_once '../config/db.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'];
    $cantidad = intval($_POST['cantidad']);
    $id_usuario_vendedor = $_SESSION['id_usuario'];

    // 1. Obtener datos del producto (Precio, Stock y Nombre para el log)
    $stmt = $conn->prepare("SELECT nombre, precio, stock FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if ($resultado && $resultado['stock'] >= $cantidad) {
        $nombre_prod = $resultado['nombre'];
        $precio_unitario = $resultado['precio'];
        $total_venta = $precio_unitario * $cantidad;

        // Iniciar transacción para asegurar integridad de los datos
        $conn->begin_transaction();

        try {
            // 2. Insertar en la tabla 'ventas'
            $stmt_venta = $conn->prepare("INSERT INTO ventas (id_producto, cantidad, total, fecha_venta) VALUES (?, ?, ?, NOW())");
            $stmt_venta->bind_param("iid", $id_producto, $cantidad, $total_venta);
            $stmt_venta->execute();

            // 3. Restar el stock en la tabla 'productos'
            $nuevo_stock = $resultado['stock'] - $cantidad;
            $stmt_update = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
            $stmt_update->bind_param("ii", $nuevo_stock, $id_producto);
            $stmt_update->execute();

            // --- REGISTRO DE ACTIVIDAD PARA EL DASHBOARD ---
            $mensaje_venta = "Realizó una venta de $cantidad unidad(es) de '$nombre_prod'. Total: $" . number_format($total_venta, 2);
            
            // Llamamos a la función definida en db.php
            registrarActividad($conn, $id_usuario_vendedor, $mensaje_venta);
            // -----------------------------------------------

            // Confirmar cambios
            $conn->commit();
            header("Location: panel.php?msj=Venta realizada con éxito");
            exit();

        } catch (Exception $e) {
            // Si algo falla en cualquier paso, deshacer todo
            $conn->rollback();
            header("Location: panel.php?error=Error en la transacción: " . $e->getMessage());
            exit();
        }
    } else {
        header("Location: panel.php?error=Stock insuficiente para realizar la venta");
        exit();
    }
} else {
    header("Location: panel.php");
    exit();
}