<?php
require_once 'includes/config.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = clean_input($_GET['id']);

try {
    // Obtener información del emprendimiento
    $stmt = $conn->prepare("SELECT e.*, c.nombre as categoria_nombre, u.nombre as usuario_nombre, u.email as usuario_email 
                           FROM emprendimientos e 
                           JOIN categorias c ON e.categoria_id = c.id 
                           JOIN usuarios u ON e.usuario_id = u.id 
                           WHERE e.id = ? AND e.activo = 1");
    $stmt->execute([$id]);
    $emprendimiento = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$emprendimiento) {
        header("Location: index.php");
        exit();
    }
    
    // Obtener imágenes adicionales
    $stmt = $conn->prepare("SELECT * FROM emprendimiento_imagenes WHERE emprendimiento_id = ?");
    $stmt->execute([$id]);
    $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = "Error al cargar el emprendimiento: " . $e->getMessage();
}

require_once 'includes/header.php';
?>

<div class="container">
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <h1><?php echo $emprendimiento['nombre']; ?></h1>
                <p class="text-muted"><i class="fas fa-tag"></i> <?php echo $emprendimiento['categoria_nombre']; ?></p>
                
                <div class="mb-4">
                    <img src="uploads/<?php echo $emprendimiento['imagen_principal']; ?>" class="img-fluid rounded" alt="<?php echo $emprendimiento['nombre']; ?>">
                </div>
                
                <?php if(!empty($imagenes)): ?>
                    <div class="row mb-4">
                        <?php foreach($imagenes as $imagen): ?>
                            <div class="col-md-3 col-6 mb-2">
                                <img src="uploads/<?php echo $imagen['imagen_url']; ?>" class="img-thumbnail" alt="Imagen adicional">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <h3>Descripción</h3>
                <p><?php echo nl2br($emprendimiento['descripcion']); ?></p>
                
                <h3>Ubicación</h3>
                <p><?php echo $emprendimiento['direccion']; ?></p>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Contacto</h4>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-phone"></i> <strong>Teléfono:</strong> <?php echo $emprendimiento['telefono']; ?></p>
                        
                        <?php if(!empty($emprendimiento['email'])): ?>
                            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo $emprendimiento['email']; ?></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($emprendimiento['website'])): ?>
                            <p><i class="fas fa-globe"></i> <strong>Sitio Web:</strong> <a href="<?php echo $emprendimiento['website']; ?>" target="_blank">Visitar sitio</a></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($emprendimiento['whatsapp'])): ?>
                            <p><i class="fab fa-whatsapp"></i> <strong>WhatsApp:</strong> <a href="https://wa.me/<?php echo $emprendimiento['whatsapp']; ?>" target="_blank">Enviar mensaje</a></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($emprendimiento['facebook'])): ?>
                            <p><i class="fab fa-facebook"></i> <strong>Facebook:</strong> <a href="<?php echo $emprendimiento['facebook']; ?>" target="_blank">Visitar página</a></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($emprendimiento['instagram'])): ?>
                            <p><i class="fab fa-instagram"></i> <strong>Instagram:</strong> <a href="<?php echo $emprendimiento['instagram']; ?>" target="_blank">Ver perfil</a></p>
                        <?php endif; ?>
                        
                        <hr>
                        <p class="text-muted"><small>Publicado por: <?php echo $emprendimiento['usuario_nombre']; ?></small></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>