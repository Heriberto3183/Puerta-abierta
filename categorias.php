<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<div class="container">
    <h2 class="text-center mb-5">Explorar por Categorías</h2>
    
    <div class="row">
        <?php
        try {
            $stmt = $conn->query("SELECT * FROM categorias");
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($categorias as $categoria) {
                echo '<div class="col-md-4 mb-4">';
                echo '  <div class="card category-card">';
                echo '    <div class="card-body text-center">';
                echo '      <i class="fas fa-'.$categoria['icono'].' fa-3x mb-3"></i>';
                echo '      <h3 class="card-title">'.$categoria['nombre'].'</h3>';
                echo '      <p class="card-text">'.$categoria['descripcion'].'</p>';
                echo '      <a href="search.php?categoria='.$categoria['id'].'" class="btn btn-primary">Ver Emprendimientos</a>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
        } catch(PDOException $e) {
            echo '<div class="col-12"><div class="alert alert-danger">Error al cargar categorías: '.$e->getMessage().'</div></div>';
        }
        ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>