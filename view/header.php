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
    <div class="carrito-header">
      <h2 class="titulo-panel">Tu Pedido</h2>

    </div>
    <div>
      <div class="producto-panel">
        <div class="tarjeta-panel">
          <img class="img-panel" src="img/albahacatailandesa.webp">
        </div>
          <p>Nombre Producto</p>
          <p>Cantidad</p>
          <p>Precio</p>
          <a><button class="">ELIMINAR</button></a>
          <hr class="linea-panel">
        </div>
        <div class="subtotal-panel">
          <p>SUBTOTAL (1 ITEMS)</p>
          <p>Precio total</p>
          <p>Envío Gratis+49€</p>
        </div>
        <a href="?controller=producto&action=carrito"><button class="boton-panel">Comprar</button></a>
        <a id="seguir-comprando" class="d-block text-center mt-3">Seguir comprando</a>
      
    </div>
  </section>

  <script src="js/panelCompra.js"></script>
</body>

</html>