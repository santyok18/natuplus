<?php
include('../src/conexion.php'); // tu archivo de conexión a la BD
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="../styles/style.css">
  <script src="../js/app.js" async></script>
  <title>Natuplus</title>
</head>

<body class="tienda-pege">
  <header>
    <h1>Natuplus</h1>
  </header>

  <div class="contenedor">
    <section class="contenedor">
      <!-- Contenedor de productos -->
      <div class="contenedor-items">
        <?php
        $sql = "SELECT * FROM producto WHERE estado = 1 AND stock > 0";
        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0):
            while ($row = $resultado->fetch_assoc()):
                $nombre = htmlspecialchars($row['nombre_producto']);
                $precio = number_format($row['precio'], 0, ',', '.');
                $foto = htmlspecialchars($row['foto']); // para la ruta de imagen
        ?>
        <div class="item">
          <span class="titulo-item"><?= $nombre ?></span>
          <img src="../img/<?= $foto ?>" alt="<?= $nombre ?>" class="img-item">
          <span class="precio-item">$<?= $precio ?></span>
          <button class="boton-item" 
              data-id="<?= $row['id_producto'] ?>" 
              data-stock="<?= $row['stock'] ?>">
              Agregar al Carrito
          </button>

        </div>
        <?php
            endwhile;
        else:
            echo "<p>No hay productos disponibles en este momento.</p>";
        endif;
        $conn->close();
        ?>
      </div>

      <!-- Carrito de Compras -->
      <div class="carrito" id="carrito">
        <div class="header-carrito">
          <h2>Tu Carrito</h2>
        </div>
        <div class="carrito-items">
          <!-- Items del carrito se agregarían acá -->
        </div>
        <div class="carrito-total">
          <div class="fila">
            <strong>Tu Total</strong>
            <span class="carrito-precio-total">$0</span>
          </div>
          <button class="btn-pagar">Pagar <i class="fa-solid fa-bag-shopping"></i></button>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
