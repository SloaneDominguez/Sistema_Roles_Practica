<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-container">

    <!-- LOGIN -->
    <div id="login-form">
        <h2>Iniciar Sesión</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-box alert-error">
                <span class="alert-icon"></span>
                <span>
                    <?php 
                        if ($_GET['error'] == 'auth') echo "Contraseña incorrecta. Intento " . htmlspecialchars($_GET['intento']) . " de 3";
                        if ($_GET['error'] == 'bloqueado') echo "Cuenta bloqueada por seguridad.";
                        if ($_GET['error'] == 'notfound') echo "El usuario no existe.";
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert-box alert-success">
        <span class="alert-icon">✔️</span>
        <span>Registro exitoso. Ahora puedes iniciar sesión.</span>
        </div>
        <?php endif; ?>

        <form action="process.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>
        <script src="https://accounts.google.com/gsi/client" async defer></script>
        <br>
        <div id="g_id_onload"
            data-client_id=<?php echo $_ENV['GOOGLE_CLIENT_ID']; ?>
            data-login_uri="http://localhost/sistema_roles/auth/process_google.php"
            data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
            data-type="standard"
            data-size="large"
            data-theme="outline"
            data-text="sign_in_with"
            data-shape="rectangular"
            data-logo_alignment="left">
        </div>

        <div class="toggle-link">
            ¿No tienes cuenta? <a href="#" onclick="toggleAuth()">Regístrate aquí</a>
        </div>
    </div>

    <!-- REGISTRO -->
    <div id="register-form" style="display: none;">
        <h2>Crear Cuenta</h2>

        <form action="process.php" method="POST">
            <input type="hidden" name="action" value="register">

            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required minlength="6">
            </div>

            <button type="submit" class="btn">Registrarse</button>
        </form>
        <script src="https://accounts.google.com/gsi/client" async defer></script>
        <br>
        <div id="g_id_onload"
            data-client_id="811468665260-n80ebrjj5aq92199cqb7bvfmo5vkj5ps.apps.googleusercontent.com"
            data-login_uri="http://localhost/sistema_roles/auth/process_google.php"
            data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
            data-type="standard"
            data-size="large"
            data-theme="outline"
            data-text="sign_in_with"
            data-shape="rectangular"
            data-logo_alignment="left">
        </div>


        <div class="toggle-link">
            ¿Ya tienes cuenta? <a href="#" onclick="toggleAuth()">Inicia sesión</a>
        </div>
    </div>

</div>

<script>
function toggleAuth() {
    const login = document.getElementById('login-form');
    const register = document.getElementById('register-form');

    if (login.style.display === 'none') {
        login.style.display = 'block';
        register.style.display = 'none';
    } else {
        login.style.display = 'none';
        register.style.display = 'block';
    }
}
</script>

</body>
</html>
