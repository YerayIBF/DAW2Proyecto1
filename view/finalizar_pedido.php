<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>April</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/finalizar-pedido.css">
    
</head>

<body>
    <header>
        <div class="header-contenedor">
            <div>
                <img src="img/Logo.png">
            </div>
            <a href="?controller=producto&action=carta"><img class="icono-bolsa" src="img/bolso-Carrito.png"></a>
        </div>
    </header>
    <hr>
    <main>
        <section class="container-fluid my-5">
            <div class="row">
                <div class="col-md-6 d-flex flex-column">
                    <h2>Entrega</h2>
                    <form method="POST" action="?controller=producto&action=finalizarPedido">
                        <select class="PaisSelect">
                            <option>España</option>
                            <option>Portugal</option>
                        </select><br>
                        <input type="text" class="inputNombre" name="nombre" placeholder="nombre" required> 
                        <input type="text" name="apellido" placeholder="apellido" required><br>
                        <input type="text" name="direccion" placeholder="Dirección" required>
                           <p><img width="20px" height="20px" src="img/info.svg">Agrega un número de domicilio si lo tienes</p>
                        <input type="text" name="vivienda" placeholder="Casa, apartamento, etc. (opcional)"><br>
                        <input type="text" name="codigoP" placeholder="Código postal" requierd>
                        <input type="text" name="ciudad" placeholder="Ciudad" requierd>
                        <input type="text" name="provincia/estado" placeholder="Provincia/Estado" requierd><br>
                        <input type="telefono" name="teléfono" placeholder="Teléfono" requierd> 
                       


                        <h2>pago</h2>
                        <P>Todas las transacciones son seguras y están encriptadas.</P>
                        <input placeholder="Número de tarjeta" name="numeroTarjeta" requierd><br>
                        <input placeholder="Fecha de vencimiento (MM / AA)" name="FechaV" requierd>
                        <input placeholder="Código de seguridad" name="CodigoSeguridad" requierd><br>
                        <input placeholder="Nombre del titular" name="numeroTarjeta" requierd><br>
                        <button type="submit">Realizar pago</button>
                    </form>
                </div>

                <div class="col-md-6 d-flex flex-column">
                <?php if (!empty($_SESSION['carrito'])) { ?>
                    <?php foreach ($_SESSION['carrito'] as $producto) { ?>
                        <div class="">
                            <img class="img-finalizar-compra" src="img/<?= $producto->getImagen(); ?>" alt="<?= $producto->getNombre(); ?>">
                            <p class="">€<?= $producto->getPrecio(); ?></p>
                            <p class=""><?= $producto->getCantidad(); ?></p>
                        </div>
                        <?php }?>
                    <?php }?>
                    <form method="POST" action="?controller=producto&action=aplicarCupon">
                        <input type="text" name="Oferta" placeholder="Añade tu descuento aqui">
                        <button type="submit">APLICAR</button>
                    </form>
                    <h2>Subtotal • <?= $cantidadArticulos ?> artículos: €<?= number_format($subtotal, 2) ?></h2>
                    <h2>Descuento en pedidos</h2>
                    <h2><?= $codigoCupon ? $codigoCupon : 'Sin cupón' ?></h2>
                    <h2>-€<?= number_format($descuentoAplicado, 2) ?></h2>
                    <h2>Envío</h2>
                    <h2>€<?= number_format($envio, 2) ?></h2>
                    <h2>Total sin impuestos</h2>
                    <h2>€<?= number_format($totalSinImpuestos, 2) ?></h2>
                    <h2>Incluye €<?= number_format($impuestos, 2) ?> de impuestos</h2>
                    <h2>Total</h2>
                    <h2>€<?= number_format($totalConImpuestos, 2) ?></h2>
                    <h2>AHORRO TOTAL</h2>
                    <h2>€<?= number_format($ahorroTotal, 2) ?></h2>
                </div>
            </div>
        </section>
                <?php if (isset($_SESSION['alerta'])): ?>
            <div class="alert alert-<?= $_SESSION['alerta']['tipo'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['alerta']['mensaje'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alerta']); // Eliminar la alerta después de mostrarla ?>
        <?php endif; ?>
        
    </main>

    <footer>

    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>