<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Configuración de la base de datos
$host     = $_ENV['DB_HOST'];
$user     = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname   = $_ENV['DB_DATABASE'];

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el juego de caracteres a UTF-8 para evitar problemas con tildes y ñ
$conn->set_charset("utf8");

/**
 * Función global para registrar acciones en el historial.
 * Se alimenta automáticamente cada vez que se llama en nuevo.php, editar.php, etc.
 */
function registrarActividad($conexion, $id_usuario, $mensaje_accion) {
    // Asegúrate de que la tabla en tu DB se llame 'actividad_logs'
    $stmt = $conexion->prepare("INSERT INTO actividad_logs (id_usuario, accion) VALUES (?, ?)");
    $stmt->bind_param("is", $id_usuario, $mensaje_accion);
    $stmt->execute();
    $stmt->close();
}

// Opcional: Activar el reporte de errores de MySQLi para desarrollo
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>