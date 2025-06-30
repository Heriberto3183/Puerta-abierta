<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<div class="hero-section bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4">Bienvenido a Puerta Abierta</h1>
        <p class="lead">Descubre y apoya a emprendedores locales en tu comunidad</p>
        <a href="categorias.php" class="btn btn-light btn-lg mt-3">Explorar Categorías</a>
    </div>
</div>

<div class="container">
    <h2 class="text-center mb-4">Emprendimientos Destacados</h2>
    
    <div class="row">
        <?php
        try {
            $stmt = $conn->query("SELECT e.*, c.nombre as categoria_nombre 
                                 FROM emprendimientos e 
                                 JOIN categorias c ON e.categoria_id = c.id 
                                 WHERE e.activo = 1 
                                 ORDER BY e.fecha_registro DESC 
                                 LIMIT 6");
            $emprendimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($emprendimientos as $emprendimiento) {
                echo '<div class="col-md-4 mb-4">';
                echo '  <div class="card h-100">';
                echo '    <img src="uploads/'.$emprendimiento['imagen_principal'].'" class="card-img-top" alt="'.$emprendimiento['nombre'].'">';
                echo '    <div class="card-body">';
                echo '      <span class="badge bg-secondary">'.$emprendimiento['categoria_nombre'].'</span>';
                echo '      <h5 class="card-title mt-2">'.$emprendimiento['nombre'].'</h5>';
                echo '      <p class="card-text">'.substr($emprendimiento['descripcion'], 0, 100).'...</p>';
                echo '    </div>';
                echo '    <div class="card-footer bg-transparent">';
                echo '      <a href="emprendimiento.php?id='.$emprendimiento['id'].'" class="btn btn-primary">Ver Detalles</a>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
        } catch(PDOException $e) {
            echo '<div class="col-12"><div class="alert alert-danger">Error al cargar emprendimientos: '.$e->getMessage().'</div></div>';
        }
        ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="categorias.php" class="btn btn-outline-primary">Ver Todos los Emprendimientos</a>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>