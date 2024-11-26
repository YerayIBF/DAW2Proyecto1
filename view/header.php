<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header April</title>
  <link rel="stylesheet" href="css/header.css">
</head>

<body>
  
  <div id="fondo-oscuro"></div>
  <section class="headerSup">
    <p><b>ENVÍO GRATIS +49€</b> A TODA LA PENÍNSULA</p>
  </section>

  <section class="headerInf">

    <div class="logo-menu">
      <div class="logo">
        <img src="img/Logo.png">
      </div>
    </div>

    <nav class="menu">
      <a href="?controller=producto&action=index">INICIO</a>
      <a href="?controller=producto&action=carta">CARTA</a>
    </nav>

    <div class="seccion-iconos">
      <input type="text" class="buscador" placeholder="Buscar">
      <a class="icono"><img src="img/iconoLupa.png"></a>
      <a class="icono" href="?controller=producto&action=verCuenta"><img src="img/iconoUsuario.png"></a>
      <a id="icono-carrito" class="icono"><img src="img/iconoCarrito.png"></a>
      <p class="numcarrito">0</p>
    </div>
  </section>

  <section id="carrito-panel">
    <div>
      <!-- tarjeta producto -->
        <div class="producto-panel">
            <h2 class="titulo-panel">Tu Pedido</h2>
            <?php if (!empty($_SESSION['carrito'])){?>
                <?php foreach ($_SESSION['carrito'] as $producto){ ?>
                    <div class="flex-tarjeta-panel">
                        <div class="tarjeta-panel">
                            <img class="img-panel" src="img/<?= $producto->getImagen(); ?>" alt="<?= $producto->getNombre(); ?>">
                        </div>
                        <div class="flex-producto-texto">
                            <p class="p-nombre-producto"><?= $producto->getNombre(); ?></p>
                            <p class="p-cantidad">x <?= $producto->getCantidad(); ?></p>
                            <p class="p-precio">€<?= $producto->getPrecio() ?></p>
                            <a class="a-eliminar" href="?controller=producto&action=eliminar&id=<?= $producto->getId(); ?>"><b>X</b> ELIMINAR</a>
                        </div>
                    </div>
                <?php }?>
            <?php } ?>
        </div>

        <hr class="linea-panel">

        <!-- Subtotal -->
        <div class="subtotal-panel">
        <?php if (!empty($_SESSION['carrito'])){ ?>
                <div class="contenedor-subtotal-compra">
                    <div class="contener-subtotal texto-subtotal">
                        <p>SUBTOTAL (<?= count($_SESSION['carrito']); ?> ITEMS)</p>
                        <p>
                            €<?= number_format(array_sum(array_map(fn($p) => $p->getCantidad() * $p->getPrecio(), $_SESSION['carrito'])), 2); ?>
                        </p>
                    </div>
                    <p class="texto-envio">Envío Gratis+49€</p>
                </div>
                <?php } else{?>
                  <div class="contenedor-subtotal-compra">
                    <div class="contener-subtotal texto-subtotal">
                        <p>SUBTOTAL (0 ITEMS)</p>
                        <p>
                            €0,00
                        </p>
                    </div>
                    <p class="texto-envio">Envío Gratis+49€</p>
                </div>
                <?php }?>
            
        </div>

        <!-- Botón Comprar -->
        <div class="contenedor-boton-panel">
            <a href="?controller=producto&action=carrito">
                <button class="boton-panel-comprar">Comprar</button>
            </a>
        </div>
        
        <!-- Botón Seguir Comprando -->
        <a id="seguir-comprando" class="d-block text-center a-panel">Seguir comprando</a>
    </div>
</section>

  <script src="js/panelCompra.js"></script>
</body>
</html>