<?php
require_once 'includes/config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = clean_input($_POST['nombre']);
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    $telefono = clean_input($_POST['telefono']);
    $direccion = clean_input($_POST['direccion']);
    
    // Validaciones
    if(empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Todos los campos marcados con * son obligatorios";
    } elseif($password != $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } elseif(strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        try {
            // Verificar si el email ya existe
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if($stmt->rowCount() > 0) {
                $error = "El email ya está registrado";
            } else {
                // Crear el usuario
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $email, $hashed_password, $telefono, $direccion]);
                
                $success = "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
            }
        } catch(PDOException $e) {
            $error = "Error al registrar: " . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registro de Emprendedor</h4>
                </div>
                <div class="card-body">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php else: ?>
                        <form method="POST" action="register.php">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre Completo *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Contraseña *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </form>
                        
                        <hr>
                        <p class="text-center">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>